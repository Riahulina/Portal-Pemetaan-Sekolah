import L from "leaflet";

const JENJANG_COLORS = {
    KB: "#EF4444",
    TK: "#3B82F6",
    SD: "#22C55E",
    SMP: "#A855F7",
    "SMA/SMK": "#F97316",
};

function mapSekolahRecord(row) {
    let social = {};
    try {
        const parsed = row.social_media ? JSON.parse(row.social_media) : null;
        social = parsed && typeof parsed === "object" ? parsed : {};
    } catch (e) {
        social = {};
    }

    const statusRaw = (row.status || "").trim().toLowerCase();
    const statusNormalized =
        statusRaw === "negeri"
            ? "Negeri"
            : statusRaw === "swasta"
              ? "Swasta"
              : row.status || "-";

    const jenjangRaw = (row.jenjang || "").trim().toUpperCase();
    const jenjangNormalized =
        jenjangRaw === "SMA" || jenjangRaw === "SMK" ? "SMA/SMK" : jenjangRaw;

    return {
        id: row.npsn,
        nama: row.nama_sekolah,
        jenjang: jenjangNormalized,
        status: statusNormalized,
        provinsi: (row.provinsi || "").trim(),
        kabupaten: (row.kabupaten_kota || "").trim(),
        kecamatan: (row.kecamatan || "").trim(),
        kelurahan: (row.kelurahan || "").trim(),
        alamat: row.alamat || "-",
        lat: parseFloat(row.latitude),
        lng: parseFloat(row.longitude),
        telepon: row.no_telepon || "-",
        email: row.email || "-",
        ig: social.instagram || "#",
        fb: social.facebook || "#",
        tiktok: social.tiktok || "#",
        murid: row.total_siswa || 0,
    };
}

async function fetchSchools() {
    try {
        const res = await fetch("/api/sekolah");
        if (!res.ok) throw new Error("Gagal mengambil data sekolah");
        const data = await res.json();
        return data
            .map(mapSekolahRecord)
            .filter((s) => !isNaN(s.lat) && !isNaN(s.lng));
    } catch (err) {
        console.error(err);
        return [];
    }
}

function buildRegionTree(schools) {
    const tree = {};
    schools.forEach((s) => {
        if (!tree[s.provinsi]) tree[s.provinsi] = {};
        if (!tree[s.provinsi][s.kabupaten]) tree[s.provinsi][s.kabupaten] = {};
        if (!tree[s.provinsi][s.kabupaten][s.kecamatan])
            tree[s.provinsi][s.kabupaten][s.kecamatan] = new Set();
        tree[s.provinsi][s.kabupaten][s.kecamatan].add(s.kelurahan);
    });
    for (const prov in tree) {
        for (const kab in tree[prov]) {
            for (const kec in tree[prov][kab]) {
                tree[prov][kab][kec] = [...tree[prov][kab][kec]].sort();
            }
        }
    }
    return tree;
}

const INDONESIA_BOUNDS = L.latLngBounds([-11.0, 94.0], [6.0, 141.0]);

let schools = [];
let regionTree = {};
let provinces = [];

let map, markersLayer;
let currentFilters = {};
let markerRefs = new Map();
let pendingPopupSchoolId = null;
let sidebarState = "filters";
let siswaChart = null;

function setSidebarState(state) {
    const filters = document.getElementById("sidebar-filters");
    const tableComponent = document.getElementById("table-component");
    const rightSidebar = document.getElementById("right-sidebar");
    const sidebarSlot = document.getElementById("sidebar-table-slot");

    if (state === "default") {
        rightSidebar.appendChild(tableComponent);
        filters.classList.remove("hidden");
        rightSidebar.classList.remove("hidden");
    } else {
        sidebarSlot.appendChild(tableComponent);
        filters.classList.add("hidden");
        rightSidebar.classList.add("hidden");
    }
    sidebarState = state;
}

function initMap() {
    map = L.map("map", {
        center: [-2.5, 118.0],
        zoom: 5,
        minZoom: 5,
        maxZoom: 19,
        zoomControl: false,
        maxBounds: INDONESIA_BOUNDS,
        maxBoundsViscosity: 1.0,
    });

    L.control.zoom({ position: "bottomright" }).addTo(map);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    markersLayer = L.layerGroup().addTo(map);

    map.on("zoomend", syncMarkersToZoom);

    map.on("moveend", () => {
        if (pendingPopupSchoolId !== null) {
            const marker = markerRefs.get(pendingPopupSchoolId);
            if (marker) marker.openPopup();
            pendingPopupSchoolId = null;
        }
    });
}

function createMarkerIcon(color) {
    return L.divIcon({
        className: "",
        html: `<svg width="28" height="40" viewBox="0 0 28 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 0C6.268 0 0 6.268 0 14c0 10.5 14 26 14 26s14-15.5 14-26C28 6.268 21.732 0 14 0z" fill="${color}" stroke="#fff" stroke-width="2"/><circle cx="14" cy="14" r="5" fill="#fff"/></svg>`,
        iconSize: [28, 40],
        iconAnchor: [14, 40],
        popupAnchor: [0, -42],
    });
}

function createClusterIcon(count, color) {
    return L.divIcon({
        className: "",
        html: `<svg width="36" height="36" viewBox="0 0 36 36" fill="none"><circle cx="18" cy="18" r="16" fill="${color}" fill-opacity="0.2" stroke="${color}" stroke-width="2"/><circle cx="18" cy="18" r="10" fill="${color}"/><text x="18" y="22" text-anchor="middle" fill="#fff" font-family="'Public Sans',sans-serif" font-weight="700" font-size="11">${count}</text></svg>`,
        iconSize: [36, 36],
        iconAnchor: [18, 18],
        popupAnchor: [0, -18],
    });
}

function hasRegionFilter(filters) {
    return !!(
        filters.provinsi ||
        filters.kabupaten ||
        filters.kecamatan ||
        filters.kelurahan
    );
}

function flyToSchool(lat, lng, schoolId) {
    pendingPopupSchoolId = schoolId;
    map.flyTo([lat, lng], 16);
}

function syncMarkersToZoom() {
    if (!map) return;
    const zoom = map.getZoom();
    const filtered = filterSchools(currentFilters);
    if (zoom >= 7) {
        renderMarkers(filtered);
    } else {
        renderAggregatedMarkers(filtered);
    }
}

function renderAggregatedMarkers(schools) {
    markersLayer.clearLayers();
    markerRefs.clear();
    const groups = {};
    schools.forEach((s) => {
        const key = s.provinsi;
        if (!groups[key])
            groups[key] = { schools: [], lat: 0, lng: 0, count: 0 };
        groups[key].schools.push(s);
        groups[key].lat += s.lat;
        groups[key].lng += s.lng;
        groups[key].count++;
    });
    const jenjangColorPool = [
        "#EF4444",
        "#3B82F6",
        "#22C55E",
        "#A855F7",
        "#F97316",
        "#0D9296",
    ];
    Object.keys(groups).forEach((prov, i) => {
        const g = groups[prov];
        const avgLat = g.lat / g.count;
        const avgLng = g.lng / g.count;
        const color = jenjangColorPool[i % jenjangColorPool.length];
        const marker = L.marker([avgLat, avgLng], {
            icon: createClusterIcon(g.count, color),
        });
        const jenjangCounts = {};
        g.schools.forEach((s) => {
            jenjangCounts[s.jenjang] = (jenjangCounts[s.jenjang] || 0) + 1;
        });
        const detail = Object.entries(jenjangCounts)
            .map(([j, c]) => `${j}: ${c}`)
            .join(" &middot; ");
        marker.bindPopup(`
            <div style="font-family:'Public Sans',sans-serif;min-width:160px;">
                <strong style="font-size:1rem;color:#1A1C1E;">${prov}</strong>
                <div style="margin:6px 0;font-size:0.85rem;color:#4b5563;">${detail}</div>
                <div style="font-size:0.85rem;color:#0D9296;font-weight:600;">Total: ${g.count} sekolah</div>
            </div>
        `);
        markersLayer.addLayer(marker);
    });
}

function renderMarkers(schools) {
    markersLayer.clearLayers();
    markerRefs.clear();
    schools.forEach((s) => {
        const color = JENJANG_COLORS[s.jenjang] || "#6B7280";
        const marker = L.marker([s.lat, s.lng], {
            icon: createMarkerIcon(color),
        });
        marker.bindPopup(`
            <div style="font-family:'Public Sans',sans-serif;min-width:180px;">
                <strong style="font-size:1rem;color:#1A1C1E;">${s.nama}</strong>
                <div style="margin:6px 0;font-size:0.85rem;color:#4b5563;">
                    ${s.jenjang} &middot; ${s.status}
                </div>
                <div style="font-size:0.85rem;color:#4b5563;">
                    ${s.kelurahan}, ${s.kecamatan}
                </div>
                <div style="margin-top:6px;display:flex;justify-content:space-between;font-size:0.85rem;">
                    <span>Murid Aktif:</span>
                    <strong style="color:#0D9296;">${s.murid.toLocaleString()}</strong>
                </div>
            </div>
        `);
        markerRefs.set(s.id, marker);
        marker.on("click", () => openSchoolDetail(s));
        markersLayer.addLayer(marker);
    });
}

function updateStatCards(schools) {
    const totalSekolah = document.getElementById("total-sekolah");
    const totalMurid = document.getElementById("total-murid");
    if (totalSekolah)
        totalSekolah.textContent = schools.length.toLocaleString();
    if (totalMurid)
        totalMurid.textContent = schools
            .reduce((sum, s) => sum + s.murid, 0)
            .toLocaleString();
}

function initSiswaChart(totalMurid) {
    const canvas = document.getElementById("siswaChart");
    if (!canvas) return;
    if (siswaChart) siswaChart.destroy();
    const kelas7 = Math.round(totalMurid * 0.35);
    const kelas8 = Math.round(totalMurid * 0.35);
    const kelas9 = totalMurid - kelas7 - kelas8;
    const labels = ["Kelas 7 SMP", "Kelas 8 SMP", "Kelas 9 SMP"];
    const values = [kelas7, kelas8, kelas9];
    const colors = ["#22C55E", "#F97316", "#3B82F6"];
    siswaChart = new Chart(canvas, {
        type: "doughnut",
        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 0,
                },
            ],
        },
        options: {
            cutout: "70%",
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true },
            },
            responsive: true,
            maintainAspectRatio: true,
        },
        plugins: [
            {
                id: "centerText",
                beforeDraw(chart) {
                    const { width, height, ctx: c } = chart;
                    c.save();
                    const cx = width / 2;
                    const cy = height / 2;
                    c.textAlign = "center";
                    c.textBaseline = "middle";
                    c.font = "700 20px 'Public Sans', sans-serif";
                    c.fillStyle = "#1A1C1E";
                    c.fillText(
                        `Total ${totalMurid.toLocaleString()}`,
                        cx,
                        cy - 8,
                    );
                    c.font = "400 11px 'Public Sans', sans-serif";
                    c.fillStyle = "#6B7280";
                    c.fillText("Siswa", cx, cy + 14);
                    c.restore();
                },
            },
        ],
    });
    const legendContainer = document.getElementById("chart-legend");
    if (legendContainer) {
        legendContainer.innerHTML = "";
        labels.forEach((label, i) => {
            const row = document.createElement("div");
            row.className = "legend-row";
            row.innerHTML = `<span class="legend-dot" style="background:${colors[i]};"></span> ${label} - <strong>${values[i].toLocaleString()}</strong>`;
            legendContainer.appendChild(row);
        });
    }
}

function openSchoolDetail(school) {
    document.getElementById("panel-nama").textContent = school.nama;

    const badge = document.getElementById("panel-status-badge");
    badge.textContent = school.status;
    badge.className =
        "status-badge " +
        (school.status === "Negeri"
            ? "status-badge--negeri"
            : "status-badge--swasta");

    document.getElementById("panel-murid").textContent =
        school.murid.toLocaleString() + " Siswa";
    document.getElementById("panel-address").textContent =
        `${school.alamat}, ${school.kelurahan}, ${school.kecamatan}, ${school.kabupaten}, ${school.provinsi}`;
    document.getElementById("panel-telepon").textContent = school.telepon;
    document.getElementById("panel-email").textContent = school.email;

    document.getElementById("panel-ig").href = school.ig;
    document.getElementById("panel-fb").href = school.fb;
    document.getElementById("panel-tiktok").href = school.tiktok;

    const gmapsBtn = document.getElementById("btn-gmaps");
    gmapsBtn.onclick = () => {
        window.open(
            `https://www.google.com/maps?q=${school.lat},${school.lng}`,
            "_blank",
        );
    };

    document.getElementById("school-detail-overlay").classList.add("open");
    setSidebarState("detail");
    initSiswaChart(school.murid);
}

function closeSchoolDetail() {
    document.getElementById("school-detail-overlay").classList.remove("open");
    setSidebarState("default");
}

function updateLegend(filteredJenjang) {
    document.querySelectorAll(".legend-item").forEach((el) => {
        const jenjang = el.dataset.jenjang;
        if (!filteredJenjang || filteredJenjang === "Semua") {
            el.style.display = "flex";
        } else {
            el.style.display = jenjang === filteredJenjang ? "flex" : "none";
        }
    });
}

function filterSchools(filters) {
    return schools.filter((s) => {
        if (
            filters.jenjang &&
            filters.jenjang !== "Semua" &&
            s.jenjang !== filters.jenjang
        )
            return false;
        if (
            filters.status &&
            filters.status !== "Semua" &&
            s.status !== filters.status
        )
            return false;
        if (filters.provinsi && s.provinsi !== filters.provinsi) return false;
        if (filters.kabupaten && s.kabupaten !== filters.kabupaten)
            return false;
        if (filters.kecamatan && s.kecamatan !== filters.kecamatan)
            return false;
        if (filters.kelurahan && s.kelurahan !== filters.kelurahan)
            return false;
        return true;
    });
}

function populateSelect(selectId, options, placeholder) {
    const sel = document.getElementById(selectId);
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    options.forEach((o) => {
        const opt = document.createElement("option");
        opt.value = o;
        opt.textContent = o;
        sel.appendChild(opt);
    });
}

function updateCascading(prov, kab, kec) {
    const kabSel = document.getElementById("filter-kabupaten");
    const kecSel = document.getElementById("filter-kecamatan");
    const kelSel = document.getElementById("filter-kelurahan");

    kabSel.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    kecSel.innerHTML = '<option value="">Pilih Kecamatan</option>';
    kelSel.innerHTML = '<option value="">Pilih Kelurahan</option>';

    if (prov && regionTree[prov]) {
        const kabList = Object.keys(regionTree[prov]).sort();
        kabList.forEach((k) => {
            const opt = document.createElement("option");
            opt.value = k;
            opt.textContent = k;
            kabSel.appendChild(opt);
        });
        if (kab && regionTree[prov][kab]) {
            kabSel.value = kab;
            const kecList = Object.keys(regionTree[prov][kab]).sort();
            kecList.forEach((k) => {
                const opt = document.createElement("option");
                opt.value = k;
                opt.textContent = k;
                kecSel.appendChild(opt);
            });
            if (kec && regionTree[prov][kab][kec]) {
                kecSel.value = kec;
                const kelList = regionTree[prov][kab][kec];
                kelList.forEach((k) => {
                    const opt = document.createElement("option");
                    opt.value = k;
                    opt.textContent = k;
                    kelSel.appendChild(opt);
                });
            }
        }
    }
}

function setupFilters() {
    populateSelect(
        "filter-jenjang",
        ["KB", "TK", "SD", "SMP", "SMA/SMK", "Semua"],
        "Pilih Jenjang",
    );
    populateSelect(
        "filter-status",
        ["Negeri", "Swasta", "Semua"],
        "Pilih Status",
    );
    populateSelect("filter-provinsi", provinces, "Pilih Provinsi");

    document
        .getElementById("filter-provinsi")
        .addEventListener("change", function () {
            updateCascading(this.value, "", "");
            document.getElementById("filter-kabupaten").value = "";
            document.getElementById("filter-kecamatan").value = "";
            document.getElementById("filter-kelurahan").value = "";
        });

    document
        .getElementById("filter-kabupaten")
        .addEventListener("change", function () {
            const prov = document.getElementById("filter-provinsi").value;
            updateCascading(prov, this.value, "");
            document.getElementById("filter-kecamatan").value = "";
            document.getElementById("filter-kelurahan").value = "";
        });

    document
        .getElementById("filter-kecamatan")
        .addEventListener("change", function () {
            const prov = document.getElementById("filter-provinsi").value;
            const kab = document.getElementById("filter-kabupaten").value;
            updateCascading(prov, kab, this.value);
            document.getElementById("filter-kelurahan").value = "";
        });

    document
        .getElementById("btn-terapkan")
        .addEventListener("click", applyFilters);
    document
        .getElementById("btn-reset")
        .addEventListener("click", resetFilters);
}

function applyFilters() {
    closeSchoolDetail();
    const filters = {
        jenjang: document.getElementById("filter-jenjang").value,
        status: document.getElementById("filter-status").value,
        provinsi: document.getElementById("filter-provinsi").value,
        kabupaten: document.getElementById("filter-kabupaten").value,
        kecamatan: document.getElementById("filter-kecamatan").value,
        kelurahan: document.getElementById("filter-kelurahan").value,
    };

    currentFilters = Object.fromEntries(
        Object.entries(filters).filter(([_, v]) => v && v !== "Semua"),
    );
    pendingPopupSchoolId = null;

    const filtered = filterSchools(filters);

    if (hasRegionFilter(currentFilters)) {
        renderMarkers(filtered);
        if (filtered.length > 0) {
            const midLat =
                filtered.reduce((s, x) => s + x.lat, 0) / filtered.length;
            const midLng =
                filtered.reduce((s, x) => s + x.lng, 0) / filtered.length;
            map.setView([midLat, midLng], filtered.length === 1 ? 15 : 10);
        }
    } else {
        renderAggregatedMarkers(filtered);
        map.setView([-2.5, 118.0], 5);
    }
    updateStatCards(filtered);
    updateLegend(filters.jenjang);
    renderTable(filtered);
}

function resetFilters() {
    closeSchoolDetail();
    document.getElementById("filter-jenjang").value = "";
    document.getElementById("filter-status").value = "";
    document.getElementById("filter-provinsi").value = "";
    document.getElementById("filter-kabupaten").innerHTML =
        '<option value="">Pilih Kabupaten/Kota</option>';
    document.getElementById("filter-kecamatan").innerHTML =
        '<option value="">Pilih Kecamatan</option>';
    document.getElementById("filter-kelurahan").innerHTML =
        '<option value="">Pilih Kelurahan</option>';

    currentFilters = {};
    pendingPopupSchoolId = null;
    renderAggregatedMarkers(schools);
    updateStatCards(schools);
    updateLegend("Semua");
    renderTable(schools);
    map.setView([-2.5, 118.0], 5);
}

function renderTable(schoolsList) {
    const searchQuery = (
        document.getElementById("table-search").value || ""
    ).toLowerCase();
    const filtered = searchQuery
        ? schoolsList.filter((s) => s.nama.toLowerCase().includes(searchQuery))
        : schoolsList;

    const itemsPerPage = 50;
    let currentPage = 1;

    function paginate(page) {
        currentPage = page;
        const totalPages = Math.max(
            1,
            Math.ceil(filtered.length / itemsPerPage),
        );
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pageData = filtered.slice(start, end);

        const tbody = document.getElementById("table-body");
        tbody.innerHTML = "";
        pageData.forEach((s) => {
            const tr = document.createElement("tr");
            tr.style.cursor = "pointer";
            tr.dataset.id = s.id;
            tr.dataset.lat = s.lat;
            tr.dataset.lng = s.lng;
            const badgeClass =
                s.status === "Negeri"
                    ? "status-badge--negeri"
                    : "status-badge--swasta";
            tr.innerHTML = `
                <td><strong>${s.nama}</strong></td>
                <td><span class="status-badge ${badgeClass}">${s.status}</span></td>
                <td style="text-align:right;font-weight:600;">${s.murid.toLocaleString()}</td>
            `;
            tr.addEventListener("click", () => {
                flyToSchool(s.lat, s.lng, s.id);
                openSchoolDetail(s);
            });
            tbody.appendChild(tr);
        });

        document.getElementById("result-count").textContent = filtered.length;
        renderPagination(page, totalPages, paginate);
    }

    paginate(1);
}

function renderPagination(current, total, callback) {
    const container = document.getElementById("pagination");
    container.innerHTML = "";

    const prevBtn = document.createElement("button");
    prevBtn.textContent = "←";
    prevBtn.disabled = current <= 1;
    prevBtn.addEventListener("click", () => callback(current - 1));
    container.appendChild(prevBtn);

    // Batasi nomor halaman yang ditampilkan supaya tidak ratusan tombol sekaligus
    const maxButtons = 7;
    let startPage = Math.max(1, current - Math.floor(maxButtons / 2));
    let endPage = Math.min(total, startPage + maxButtons - 1);
    if (endPage - startPage + 1 < maxButtons) {
        startPage = Math.max(1, endPage - maxButtons + 1);
    }

    if (startPage > 1) {
        const firstBtn = document.createElement("button");
        firstBtn.textContent = "1";
        firstBtn.addEventListener("click", () => callback(1));
        container.appendChild(firstBtn);
        if (startPage > 2) {
            const dots = document.createElement("span");
            dots.textContent = "...";
            dots.className = "pagination__dots";
            container.appendChild(dots);
        }
    }

    for (let i = startPage; i <= endPage; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        if (i === current) btn.classList.add("active");
        btn.addEventListener("click", () => callback(i));
        container.appendChild(btn);
    }

    if (endPage < total) {
        if (endPage < total - 1) {
            const dots = document.createElement("span");
            dots.textContent = "...";
            dots.className = "pagination__dots";
            container.appendChild(dots);
        }
        const lastBtn = document.createElement("button");
        lastBtn.textContent = total;
        lastBtn.addEventListener("click", () => callback(total));
        container.appendChild(lastBtn);
    }

    const nextBtn = document.createElement("button");
    nextBtn.textContent = "→";
    nextBtn.disabled = current >= total;
    nextBtn.addEventListener("click", () => callback(current + 1));
    container.appendChild(nextBtn);

    const info = document.createElement("span");
    info.className = "pagination__info";
    info.textContent = `Halaman ${current} dari ${total}`;
    container.appendChild(info);
}

function setupTableSearch() {
    document.getElementById("table-search").addEventListener("input", () => {
        const filtered = filterSchools(currentFilters);
        renderTable(filtered);
    });
}

function setupPanelClose() {
    document
        .getElementById("panel-close-btn")
        .addEventListener("click", closeSchoolDetail);
    document
        .getElementById("school-detail-overlay")
        .addEventListener("click", function (e) {
            if (e.target === this) closeSchoolDetail();
        });
}

document.addEventListener("DOMContentLoaded", async () => {
    initMap();

    setTimeout(() => {
        if (map) map.invalidateSize();
    }, 200);

    let resizeTimer;
    window.addEventListener("resize", () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (map) map.invalidateSize();
        }, 150);
    });

    // Tampilkan status loading di dropdown & counter selama data dimuat
    const resultCount = document.getElementById("result-count");
    if (resultCount) resultCount.textContent = "...";
    populateSelect("filter-jenjang", [], "Memuat data...");
    populateSelect("filter-status", [], "Memuat data...");
    populateSelect("filter-provinsi", [], "Memuat data...");

    schools = await fetchSchools();
    regionTree = buildRegionTree(schools);
    provinces = Object.keys(regionTree).sort();

    setupFilters();
    setupTableSearch();
    setupPanelClose();

    setSidebarState("default");

    renderAggregatedMarkers(schools);
    updateStatCards(schools);
    updateLegend("Semua");
    renderTable(schools);
});
