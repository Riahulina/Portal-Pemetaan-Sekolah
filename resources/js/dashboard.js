import L from "leaflet";
import "leaflet.markercluster";
import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.default.css";
import {
    fetchPulau,
    fetchProvinsi,
    fetchKabupaten,
    fetchKecamatan,
} from "./wilayah";

const tomSelectInstances = {};

const JENJANG_COLORS = {
    KB: "#EF4444",
    TK: "#3B82F6",
    SD: "#22C55E",
    SMP: "#A855F7",
    "SMA/SMK": "#F97316",
};

const INDONESIA_BOUNDS = L.latLngBounds([-15.0, 90.0], [12.0, 145.0]);
const TABLE_PAGE_SIZE = 20;
const SEARCH_DEBOUNCE_MS = 300;

let schools = [];
let nationalTotals = { sekolah: 0, siswa: 0 };

let map, clusterGroup, aggregatedLayer, summaryLayer;
let currentFilters = {};
let markerRefs = new Map();
let pendingPopupSchoolId = null;
let sidebarState = "filters";
let siswaChart = null;

let detailLayer;
let _detailMarkerSchoolId = null;
let _currentSchoolNpsn = null;

let _filterCacheKey = null;
let _filterCacheResult = null;

function debounce(fn, delay) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
    };
}

function filterSchools(filters) {
    const key = JSON.stringify(filters);
    if (_filterCacheKey === key) return _filterCacheResult;

    const result = schools.filter((s) => {
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
        return true;
    });

    _filterCacheKey = key;
    _filterCacheResult = result;
    return result;
}

function invalidateFilterCache() {
    _filterCacheKey = null;
    _filterCacheResult = null;
}

function mapSekolahRecord(row) {
    const statusRaw = (row.status || "").trim().toUpperCase();
    const statusNormalized =
        statusRaw === "NEGERI"
            ? "NEGERI"
            : statusRaw === "SWASTA"
              ? "SWASTA"
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
        alamat: (row.alamat || "").trim(),
        lat: parseFloat(row.latitude),
        lng: parseFloat(row.longitude),
        murid: parseInt(row.total_siswa, 10) || 0,
    };
}

async function fetchFilteredSchools(filters) {
    try {
        const params = new URLSearchParams();
        if (filters.provinsi) params.set("provinsi", filters.provinsi);
        if (filters.kabupaten) params.set("kabupaten", filters.kabupaten);
        if (filters.kecamatan) params.set("kecamatan", filters.kecamatan);
        if (filters.jenjang && filters.jenjang !== "Semua")
            params.set("jenjang", filters.jenjang);
        if (filters.status && filters.status !== "Semua")
            params.set("status", filters.status);

        if (params.toString() === "") return [];

        const res = await fetch(`/api/sekolah?${params.toString()}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const ct = res.headers.get("content-type") || "";
        if (!ct.includes("application/json")) {
            throw new Error("Response bukan JSON (kemungkinan HTML error)");
        }
        const data = await res.json();
        return data
            .map(mapSekolahRecord)
            .filter((s) => !isNaN(s.lat) && !isNaN(s.lng));
    } catch (err) {
        console.error(
            "[SatuPeta] Gagal memuat data sekolah:",
            err.message || err,
        );
        return [];
    }
}

async function fetchAndRenderSummary() {
    try {
        const res = await fetch("/api/sekolah/summary");
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const ct = res.headers.get("content-type") || "";
        if (!ct.includes("application/json")) {
            throw new Error("Response bukan JSON (kemungkinan HTML error)");
        }
        const rows = await res.json();
        if (!rows || rows.length === 0) return;

        let grandTotalSekolah = 0;
        let grandTotalSiswa = 0;

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const lat = parseFloat(row.lat);
            const lng = parseFloat(row.lng);
            const totalSekolah = parseInt(row.total_sekolah, 10) || 0;
            const totalSiswa = parseInt(row.total_siswa, 10) || 0;

            grandTotalSekolah += totalSekolah;
            grandTotalSiswa += totalSiswa;

            if (isNaN(lat) || isNaN(lng)) continue;

            const marker = L.marker([lat, lng], {
                icon: createMarkerIcon("#0D9296"),
            });

            marker.bindPopup(
                `<div style="font-family:'Public Sans',sans-serif;min-width:180px;">
                    <strong style="font-size:1rem;color:#1A1C1E;">${row.provinsi}</strong>
                    <div style="margin:6px 0;font-size:0.85rem;color:#4b5563;">
                        Total Sekolah: <strong>${totalSekolah.toLocaleString()}</strong>
                    </div>
                    <div style="font-size:0.85rem;color:#4b5563;">
                        Total Siswa: <strong>${totalSiswa.toLocaleString()}</strong>
                    </div>
                    <div style="margin-top:8px;font-size:0.8rem;color:#0D9296;font-style:italic;">
                        Pilih filter wilayah untuk melihat detail.
                    </div>
                </div>`,
                { autoPan: true, autoPanPadding: [50, 50] },
            );

            summaryLayer.addLayer(marker);
        }

        const totalSekolahEl = document.getElementById("total-sekolah");
        const totalMuridEl = document.getElementById("total-murid");
        if (totalSekolahEl)
            totalSekolahEl.textContent = grandTotalSekolah.toLocaleString();
        if (totalMuridEl)
            totalMuridEl.textContent = grandTotalSiswa.toLocaleString();

        nationalTotals = { sekolah: grandTotalSekolah, siswa: grandTotalSiswa };
    } catch (err) {
        console.error(
            "[SatuPeta] Gagal memuat ringkasan provinsi:",
            err.message || err,
        );
    }
}

async function updateSummaryCards(pulau) {
    const totalSekolahEl = document.getElementById("total-sekolah");
    const totalMuridEl = document.getElementById("total-murid");

    const apply = (sekolah, siswa) => {
        if (totalSekolahEl)
            totalSekolahEl.textContent = sekolah.toLocaleString();
        if (totalMuridEl) totalMuridEl.textContent = siswa.toLocaleString();
    };

    if (!pulau) {
        apply(nationalTotals.sekolah, nationalTotals.siswa);
        return;
    }

    try {
        const res = await fetch(
            `/api/sekolah/summary?pulau=${encodeURIComponent(pulau)}`,
        );
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const ct = res.headers.get("content-type") || "";
        if (!ct.includes("application/json")) {
            throw new Error("Response bukan JSON (kemungkinan HTML error)");
        }
        const rows = await res.json();
        let totalSekolah = 0;
        let totalSiswa = 0;
        (rows || []).forEach((row) => {
            totalSekolah += parseInt(row.total_sekolah, 10) || 0;
            totalSiswa += parseInt(row.total_siswa, 10) || 0;
        });
        apply(totalSekolah, totalSiswa);
    } catch (err) {
        console.error(
            "[SatuPeta] Gagal memuat ringkasan pulau:",
            err.message || err,
        );
        apply(nationalTotals.sekolah, nationalTotals.siswa);
    }
}

function buildPopupContent(s) {
    return `<div style="font-family:'Public Sans',sans-serif;min-width:180px;">
        <strong style="font-size:1rem;color:#1A1C1E;">${s.nama}</strong>
        <div style="margin:6px 0;font-size:0.85rem;color:#4b5563;">
            ${s.jenjang} &middot; ${s.status}
        </div>
        <div style="font-size:0.85rem;color:#4b5563;">
            ${s.alamat}, ${s.kecamatan}
        </div>
        <div style="margin-top:6px;display:flex;justify-content:space-between;font-size:0.85rem;">
            <span>Murid Aktif:</span>
            <strong style="color:#0D9296;">${s.murid.toLocaleString()}</strong>
        </div>
    </div>`;
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

function createClusterIcon(cluster) {
    const count = cluster.getChildCount();
    const size = count < 10 ? 40 : count < 100 ? 50 : 60;
    const innerR = Math.round(size * 0.3);
    const fontSize = count < 100 ? 13 : 11;
    return L.divIcon({
        html: `<div style="width:${size}px;height:${size}px;border-radius:50%;background:rgba(13,146,150,0.15);border:2px solid #0D9296;display:flex;align-items:center;justify-content:center;"><div style="width:${innerR * 2}px;height:${innerR * 2}px;border-radius:50%;background:#0D9296;display:flex;align-items:center;justify-content:center;font-family:'Public Sans',sans-serif;font-weight:700;font-size:${fontSize}px;color:#fff;">${count}</div></div>`,
        className: "",
        iconSize: [size, size],
        iconAnchor: [size / 2, size / 2],
    });
}

function hasRegionFilter(filters) {
    return !!(
        filters.pulau ||
        filters.provinsi ||
        filters.kabupaten ||
        filters.kecamatan
    );
}

function flyToSchool(lat, lng, schoolId) {
    pendingPopupSchoolId = schoolId;
    map.flyTo([lat, lng], 16);
}

function showClusterView() {
    if (!map.hasLayer(clusterGroup)) map.addLayer(clusterGroup);
    if (map.hasLayer(aggregatedLayer)) map.removeLayer(aggregatedLayer);
}

function showAggregatedView() {
    if (map.hasLayer(clusterGroup)) map.removeLayer(clusterGroup);
    if (!map.hasLayer(aggregatedLayer)) map.addLayer(aggregatedLayer);
}

function updateLayerVisibility() {
    if (!map) return;
    const zoom = map.getZoom();
    if (zoom >= 7) {
        showClusterView();
    } else {
        showAggregatedView();
    }
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

    clusterGroup = L.markerClusterGroup({
        chunkedLoading: true,
        chunkInterval: 100,
        chunkDelay: 10,
        maxClusterRadius: 60,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        iconCreateFunction: createClusterIcon,
    });

    aggregatedLayer = L.layerGroup().addTo(map);
    summaryLayer = L.layerGroup().addTo(map);
    detailLayer = L.layerGroup().addTo(map);

    map.on("zoomend", updateLayerVisibility);

    map.on("moveend", () => {
        if (pendingPopupSchoolId !== null) {
            const schoolId = pendingPopupSchoolId;
            pendingPopupSchoolId = null;
            setTimeout(() => {
                const marker = markerRefs.get(schoolId);
                if (marker && clusterGroup.hasLayer(marker)) {
                    clusterGroup.zoomToShowLayer(marker, () => {
                        marker.openPopup();
                    });
                }
            }, 200);
        }
    });
}

function renderMarkers(schoolsList) {
    clusterGroup.clearLayers();
    markerRefs.clear();
    for (let i = 0; i < schoolsList.length; i++) {
        const s = schoolsList[i];
        const color = JENJANG_COLORS[s.jenjang] || "#6B7280";
        const marker = L.marker([s.lat, s.lng], {
            icon: createMarkerIcon(color),
        });
        marker.bindPopup(buildPopupContent(s), {
            autoPan: true,
            autoPanPadding: [50, 50],
        });
        markerRefs.set(s.id, marker);
        marker.on("click", () => openSchoolDetail(s));
        clusterGroup.addLayer(marker);
    }
    updateInfoBadge();
}

function renderAggregatedMarkers(schoolsList) {
    aggregatedLayer.clearLayers();
    markerRefs.clear();

    const groups = {};
    for (let i = 0; i < schoolsList.length; i++) {
        const s = schoolsList[i];
        const key = s.provinsi;
        if (!groups[key]) {
            groups[key] = { lat: 0, lng: 0, count: 0, jenjang: {} };
        }
        groups[key].lat += s.lat;
        groups[key].lng += s.lng;
        groups[key].count++;
        groups[key].jenjang[s.jenjang] =
            (groups[key].jenjang[s.jenjang] || 0) + 1;
    }

    const jenjangColorPool = [
        "#EF4444",
        "#3B82F6",
        "#22C55E",
        "#A855F7",
        "#F97316",
        "#0D9296",
    ];
    const groupKeys = Object.keys(groups);
    for (let i = 0; i < groupKeys.length; i++) {
        const prov = groupKeys[i];
        const g = groups[prov];
        const avgLat = g.lat / g.count;
        const avgLng = g.lng / g.count;
        const color = jenjangColorPool[i % jenjangColorPool.length];

        const marker = L.marker([avgLat, avgLng], {
            icon: createMarkerIcon(color),
        });

        const detail = Object.entries(g.jenjang)
            .map(([j, c]) => `${j}: ${c}`)
            .join(" &middot; ");
        marker.bindPopup(
            `<div style="font-family:'Public Sans',sans-serif;min-width:160px;">
                <strong style="font-size:1rem;color:#1A1C1E;">${prov}</strong>
                <div style="margin:6px 0;font-size:0.85rem;color:#4b5563;">${detail}</div>
                <div style="font-size:0.85rem;color:#0D9296;font-weight:600;">Total: ${g.count} sekolah</div>
            </div>`,
            { autoPan: true, autoPanPadding: [50, 50] },
        );
        aggregatedLayer.addLayer(marker);
    }
    updateInfoBadge();
}

function updateStatCards(schoolsList) {
    const totalSekolah = document.getElementById("total-sekolah");
    const totalMurid = document.getElementById("total-murid");
    if (totalSekolah)
        totalSekolah.textContent = schoolsList.length.toLocaleString();
    if (totalMurid)
        totalMurid.textContent = schoolsList
            .reduce((sum, s) => sum + s.murid, 0)
            .toLocaleString();
}

function initSiswaChart(siswaLaki, siswaPerempuan) {
    const canvas = document.getElementById("siswaChart");
    if (!canvas) return;

    if (siswaChart) siswaChart.destroy();

    const labels = ["Laki-laki", "Perempuan"];
    const values = [siswaLaki, siswaPerempuan];
    const colors = ["#3B82F6", "#EC4899"];

    const totalMurid = siswaLaki + siswaPerempuan;

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
            },
        },
        plugins: [
            {
                id: "centerText",
                beforeDraw(chart) {
                    const { width, height, ctx } = chart;

                    ctx.save();
                    ctx.textAlign = "center";
                    ctx.textBaseline = "middle";

                    ctx.font = "700 20px Inter";
                    ctx.fillStyle = "#1A1C1E";
                    ctx.fillText(
                        `Total ${totalMurid}`,
                        width / 2,
                        height / 2 - 8,
                    );

                    ctx.font = "400 11px Inter";
                    ctx.fillStyle = "#6B7280";
                    ctx.fillText("Murid", width / 2, height / 2 + 14);

                    ctx.restore();
                },
            },
        ],
    });

    const legend = document.getElementById("chart-legend");
    legend.innerHTML = "";

    labels.forEach((label, i) => {
        legend.innerHTML += `
            <div class="legend-row">
                <span class="legend-dot" style="background:${colors[i]}"></span>
                ${label} - <strong>${values[i].toLocaleString()}</strong>
            </div>
        `;
    });
}

async function openSchoolDetail(school) {
    _currentSchoolNpsn = school.id;
    document.getElementById("panel-nama").textContent = school.nama;

    const badge = document.getElementById("panel-status-badge");
    badge.textContent = school.status;
    badge.className =
        "status-badge " +
        (school.status === "NEGERI"
            ? "status-badge--negeri"
            : "status-badge--swasta");

    document.getElementById("panel-murid").textContent =
        school.murid.toLocaleString() + " Siswa";
    document.getElementById("panel-address").textContent =
        `${school.kelurahan}, ${school.kecamatan}, ${school.kabupaten}, ${school.provinsi}`;
    document.getElementById("panel-telepon").textContent = "Memuat...";
    document.getElementById("panel-email").textContent = "Memuat...";

    const sosmedSection = document.getElementById("social-media-section");
    if (sosmedSection) sosmedSection.style.display = "none";
    [
        "btn-sosmed-ig",
        "btn-sosmed-fb",
        "btn-sosmed-tiktok",
        "btn-sosmed-web",
    ].forEach((id) => {
        const btn = document.getElementById(id);
        if (btn) btn.style.display = "none";
    });

    const warningCard = document.getElementById("data-warning-card");
    const warningBody = document.getElementById("data-warning-body");
    if (warningCard) warningCard.classList.add("hidden");
    if (warningBody) warningBody.innerHTML = "";

    const gmapsBtn = document.getElementById("btn-gmaps");
    gmapsBtn.onclick = () => {
        window.open(
            `https://www.google.com/maps?q=${school.lat},${school.lng}`,
            "_blank",
        );
    };

    document.getElementById("school-detail-overlay").classList.add("open");
    document.body.classList.add("mobile-detail-active");
    setSidebarState("detail");

    if (!hasRegionFilter(currentFilters)) {
        detailLayer.clearLayers();
        const color = JENJANG_COLORS[school.jenjang] || "#6B7280";
        const marker = L.marker([school.lat, school.lng], {
            icon: createMarkerIcon(color),
        });
        marker.bindPopup(buildPopupContent(school), {
            autoPan: true,
            autoPanPadding: [50, 50],
        });
        marker.on("click", () => openSchoolDetail(school));
        detailLayer.addLayer(marker);
        _detailMarkerSchoolId = school.id;
        pendingPopupSchoolId = null;
        map.flyTo([school.lat, school.lng], 16);
        setTimeout(() => marker.openPopup(), 400);
    } else {
        _detailMarkerSchoolId = null;
        pendingPopupSchoolId = school.id;
        map.flyTo([school.lat, school.lng], 16);
    }

    updateInfoBadge();

    try {
        const res = await fetch(`/api/sekolah/${school.id}/detail`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const ct = res.headers.get("content-type") || "";
        if (!ct.includes("application/json")) {
            throw new Error("Response bukan JSON");
        }
        const detail = await res.json();
        console.log(detail);

        initSiswaChart(
            parseInt(detail.jumlah_siswa_laki_laki || 0),
            parseInt(detail.jumlah_siswa_perempuan || 0),
        );

        const telepon = detail.no_telepon || "-";
        const email = detail.email || "-";
        const alamat = detail.alamat || "-";
        const socialMedia = detail.social_media || "";

        document.getElementById("panel-telepon").textContent = telepon;
        document.getElementById("panel-email").textContent = email;
        document.getElementById("panel-address").textContent =
            `${alamat}, ${school.kelurahan}, ${school.kecamatan}, ${school.kabupaten}, ${school.provinsi}`;

        let social = {};
        try {
            const parsed = socialMedia ? JSON.parse(socialMedia) : null;
            social = parsed && typeof parsed === "object" ? parsed : {};
        } catch (e) {
            social = {};
        }

        const ig = social.instagram || "";
        const fb = social.facebook || "";
        const tiktok = social.tiktok || "";

        const urlRegex = /^(https?:\/\/)?([\w\d-]+\.)+\w{2,}(\/.*)?$/i;
        const rawUrl = (socialMedia || ig || fb || tiktok || "").trim();
        const isSosmedEmpty = !rawUrl || !urlRegex.test(rawUrl);

        if (!isSosmedEmpty) {
            let cleanedUrl = rawUrl;
            if (!/^https?:\/\//i.test(cleanedUrl)) {
                cleanedUrl = "https://" + cleanedUrl;
            }

            const lower = cleanedUrl.toLowerCase();
            if (sosmedSection) sosmedSection.style.display = "flex";

            if (lower.includes("instagram.com")) {
                const btn = document.getElementById("btn-sosmed-ig");
                if (btn) {
                    btn.setAttribute("href", cleanedUrl);
                    btn.style.display = "inline-flex";
                }
            } else if (
                lower.includes("facebook.com") ||
                lower.includes("fb.com")
            ) {
                const btn = document.getElementById("btn-sosmed-fb");
                if (btn) {
                    btn.setAttribute("href", cleanedUrl);
                    btn.style.display = "inline-flex";
                }
            } else if (lower.includes("tiktok.com")) {
                const btn = document.getElementById("btn-sosmed-tiktok");
                if (btn) {
                    btn.setAttribute("href", cleanedUrl);
                    btn.style.display = "inline-flex";
                }
            } else {
                const btn = document.getElementById("btn-sosmed-web");
                if (btn) {
                    btn.setAttribute("href", cleanedUrl);
                    btn.style.display = "inline-flex";
                }
            }
        }

        const isPhoneEmpty = !telepon || telepon === "-";
        const isEmailEmpty = !email || email === "-";
        const isMuridIncomplete = !school.murid || school.murid <= 2;
        const isKoordinatEmpty =
            !school.lat ||
            !school.lng ||
            isNaN(school.lat) ||
            isNaN(school.lng);
        const hasIncompleteData =
            isPhoneEmpty ||
            isEmailEmpty ||
            isMuridIncomplete ||
            isKoordinatEmpty ||
            isSosmedEmpty;

        if (hasIncompleteData) {
            warningCard.classList.remove("hidden");
            let rows = "";
            if (isPhoneEmpty) {
                rows += `<div class="data-warning-row"><span class="data-warning-row__label">Nomor Telepon</span><span class="data-warning-badge data-warning-badge--red">Kosong</span></div>`;
            }
            if (isEmailEmpty) {
                rows += `<div class="data-warning-row"><span class="data-warning-row__label">Email</span><span class="data-warning-badge data-warning-badge--red">Kosong</span></div>`;
            }
            if (isMuridIncomplete) {
                rows += `<div class="data-warning-row"><span class="data-warning-row__label">Jumlah Siswa</span><span class="data-warning-badge data-warning-badge--orange">Belum Lengkap</span></div>`;
            }
            if (isKoordinatEmpty) {
                rows += `<div class="data-warning-row"><span class="data-warning-row__label">Titik Koordinat</span><span class="data-warning-badge data-warning-badge--orange">Belum Terdaftar</span></div>`;
            }
            if (isSosmedEmpty) {
                rows += `<div class="data-warning-row"><span class="data-warning-row__label">Media Sosial/Website</span><span class="data-warning-badge data-warning-badge--red">Kosong</span></div>`;
            }
            rows += `<div class="data-warning-row"><span class="data-warning-row__label">Status Sekolah</span><span class="data-warning-badge data-warning-badge--green">Terverifikasi</span></div>`;
            warningBody.innerHTML = rows;
        } else {
            warningCard.classList.add("hidden");
            warningBody.innerHTML = "";
        }
    } catch (err) {
        console.error(
            "[SatuPeta] Gagal memuat detail sekolah:",
            err.message || err,
        );
        document.getElementById("panel-telepon").textContent = "-";
        document.getElementById("panel-email").textContent = "-";
    }
}

function closeSchoolDetail() {
    document.getElementById("school-detail-overlay").classList.remove("open");
    document.body.classList.remove("mobile-detail-active");
    setSidebarState("default");
    _currentSchoolNpsn = null;

    const warningCard = document.getElementById("data-warning-card");
    const warningBody = document.getElementById("data-warning-body");
    if (warningCard) warningCard.classList.add("hidden");
    if (warningBody) warningBody.innerHTML = "";

    if (_detailMarkerSchoolId !== null) {
        detailLayer.clearLayers();
        _detailMarkerSchoolId = null;
    }
    pendingPopupSchoolId = null;

    updateInfoBadge();
}

function updateInfoBadge() {
    const badge = document.getElementById("map-info-badge");
    if (!badge) return;
    const isDetailOpen = document
        .getElementById("school-detail-overlay")
        .classList.contains("open");
    const hasFilter = hasRegionFilter(currentFilters);
    if (isDetailOpen || hasFilter) {
        badge.classList.add("hidden");
    } else {
        badge.classList.remove("hidden");
    }
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

function populateSelect(selectId, options, placeholder) {
    const sel = document.getElementById(selectId);
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    options.forEach((o) => {
        const opt = document.createElement("option");
        opt.value = o;
        opt.textContent = o;
        sel.appendChild(opt);
    });

    if (tomSelectInstances[selectId]) {
        tomSelectInstances[selectId].destroy();
    }
    tomSelectInstances[selectId] = new TomSelect(sel, {
        allowEmptyOption: true,
        create: false,
        searchField: ["text"],
        placeholder: placeholder,
    });
}

function repopulateTomSelect(selectId, options, placeholder) {
    const instance = tomSelectInstances[selectId];
    if (!instance) return;
    instance.clear();
    instance.clearOptions();
    instance.addOption({ value: "", text: placeholder || "Pilih" });
    instance.addOptions(options.map((o) => ({ value: o, text: o })));
}

function setSelectEnabled(selectId, enabled) {
    const instance = tomSelectInstances[selectId];
    if (!instance) return;
    if (enabled) instance.enable();
    else instance.disable();
}

let _cascadeSeq = 0;

async function loadKabupatenOptions(prov) {
    const seq = ++_cascadeSeq;
    const kabInstance = tomSelectInstances["filter-kabupaten"];
    const kecInstance = tomSelectInstances["filter-kecamatan"];

    kabInstance.clear();
    kabInstance.clearOptions();
    kabInstance.addOption({ value: "", text: "Pilih Kabupaten/Kota" });

    kecInstance.clear();
    kecInstance.clearOptions();
    kecInstance.addOption({ value: "", text: "Pilih Kecamatan" });

    if (!prov) {
        setSelectEnabled("filter-kabupaten", false);
        setSelectEnabled("filter-kecamatan", false);
        return;
    }

    let kabList = [];
    try {
        kabList = await fetchKabupaten(prov);
    } catch (err) {
        console.error(
            "[SatuPeta] Gagal memuat kabupaten/kota:",
            err.message || err,
        );
    }
    if (seq !== _cascadeSeq) return;

    if (!kabList.length) {
        setSelectEnabled("filter-kabupaten", false);
        setSelectEnabled("filter-kecamatan", false);
        return;
    }

    kabInstance.addOptions(kabList.map((k) => ({ value: k, text: k })));
    setSelectEnabled("filter-kabupaten", true);
    setSelectEnabled("filter-kecamatan", false);
}

async function loadKecamatanOptions(kab) {
    const seq = ++_cascadeSeq;
    const kecInstance = tomSelectInstances["filter-kecamatan"];

    kecInstance.clear();
    kecInstance.clearOptions();
    kecInstance.addOption({ value: "", text: "Pilih Kecamatan" });

    if (!kab) {
        setSelectEnabled("filter-kecamatan", false);
        return;
    }

    let kecList = [];
    try {
        kecList = await fetchKecamatan(kab);
    } catch (err) {
        console.error("[SatuPeta] Gagal memuat kecamatan:", err.message || err);
    }
    if (seq !== _cascadeSeq) return;

    if (!kecList.length) {
        setSelectEnabled("filter-kecamatan", false);
        return;
    }

    kecInstance.addOptions(kecList.map((k) => ({ value: k, text: k })));
    setSelectEnabled("filter-kecamatan", true);
}

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

function showFilterWarning(message) {
    const warning = document.getElementById("filter-warning");

    if (!warning) {
        alert(message);
        return;
    }

    warning.textContent = message;
    warning.style.display = "block";

    clearTimeout(warning._timer);

    warning._timer = setTimeout(() => {
        warning.style.display = "none";
    }, 3000);
}

function hideFilterWarning() {
    const el = document.getElementById("filter-warning");
    if (!el) return;

    el.classList.remove("show");
}

async function applyFilters() {
    const btn = document.getElementById("btn-terapkan");
    const btnText = btn.querySelector(".btn-text");
    const btnLoading = btn.querySelector(".btn-loading");

    btn.disabled = true;
    btnText.style.display = "none";
    btnLoading.style.display = "flex";

    try {
        closeSchoolDetail();
        invalidateFilterCache();
        detailLayer.clearLayers();
        _detailMarkerSchoolId = null;

        const filters = {
            pulau: tomSelectInstances["filter-pulau"].getValue(),
            jenjang: tomSelectInstances["filter-jenjang"].getValue(),
            status: tomSelectInstances["filter-status"].getValue(),
            provinsi: tomSelectInstances["filter-provinsi"].getValue(),
            kabupaten: tomSelectInstances["filter-kabupaten"].getValue(),
            kecamatan: tomSelectInstances["filter-kecamatan"].getValue(),
        };

        // Belum pilih Pulau
        if (!filters.jenjang) {
            showFilterWarning("Silakan pilih Jenjang terlebih dahulu.");

            btn.disabled = false;
            btnText.style.display = "inline";
            btnLoading.style.display = "none";
            return;
        }
        if (!filters.status) {
            showFilterWarning("Silakan pilih Status terlebih dahulu.");

            btn.disabled = false;
            btnText.style.display = "inline";
            btnLoading.style.display = "none";
            return;
        }

        // Sudah pilih Pulau tapi belum pilih Provinsi
        if (!filters.pulau) {
            showFilterWarning("Silakan pilih Pulau terlebih dahulu.");

            btn.disabled = false;
            btnText.style.display = "inline";
            btnLoading.style.display = "none";
            return;
        }

        currentFilters = Object.fromEntries(
            Object.entries(filters).filter(([_, v]) => v && v !== "Semua"),
        );

        pendingPopupSchoolId = null;

        if (!hasRegionFilter(currentFilters)) {
            schools = [];
            summaryLayer.clearLayers();
            renderAggregatedMarkers(schools);
            updateSummaryCards(currentFilters.pulau || "");
            updateLegend(filters.jenjang);
            renderTable(schools);
            map.setView([-2.5, 118.0], 5);
            return;
        }

        summaryLayer.clearLayers();

        const resultCount = document.getElementById("result-count");
        if (resultCount) resultCount.textContent = "Memuat...";

        schools = await fetchFilteredSchools(currentFilters);

        renderMarkers(schools);

        if (schools.length > 0) {
            const midLat =
                schools.reduce((s, x) => s + x.lat, 0) / schools.length;

            const midLng =
                schools.reduce((s, x) => s + x.lng, 0) / schools.length;

            map.setView([midLat, midLng], schools.length === 1 ? 15 : 10);
        }

        updateStatCards(schools);
        updateLegend(filters.jenjang);
        renderTable(schools);
    } finally {
        btn.disabled = false;
        btnText.style.display = "inline";
        btnLoading.style.display = "none";
    }
}

function resetFilters() {
    closeSchoolDetail();
    invalidateFilterCache();
    _cascadeSeq++;

    tomSelectInstances["filter-jenjang"].clear();
    tomSelectInstances["filter-status"].clear();
    tomSelectInstances["filter-pulau"].clear();
    tomSelectInstances["filter-provinsi"].clear();
    repopulateTomSelect("filter-provinsi", [], "Pilih Provinsi");
    tomSelectInstances["filter-kabupaten"].clear();
    tomSelectInstances["filter-kabupaten"].clearOptions();
    tomSelectInstances["filter-kabupaten"].addOption({
        value: "",
        text: "Pilih Kabupaten/Kota",
    });
    tomSelectInstances["filter-kecamatan"].clear();
    tomSelectInstances["filter-kecamatan"].clearOptions();
    tomSelectInstances["filter-kecamatan"].addOption({
        value: "",
        text: "Pilih Kecamatan",
    });
    setSelectEnabled("filter-provinsi", false);
    setSelectEnabled("filter-kabupaten", false);
    setSelectEnabled("filter-kecamatan", false);

    currentFilters = {};
    pendingPopupSchoolId = null;
    schools = [];

    clusterGroup.clearLayers();
    summaryLayer.clearLayers();
    detailLayer.clearLayers();
    _detailMarkerSchoolId = null;
    renderAggregatedMarkers(schools);
    updateSummaryCards("");
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

    function paginate(page) {
        const totalPages = Math.max(
            1,
            Math.ceil(filtered.length / TABLE_PAGE_SIZE),
        );
        const start = (page - 1) * TABLE_PAGE_SIZE;
        const end = start + TABLE_PAGE_SIZE;
        const pageData = filtered.slice(start, end);

        const tbody = document.getElementById("table-body");
        const fragment = document.createDocumentFragment();

        for (let i = 0; i < pageData.length; i++) {
            const s = pageData[i];
            const tr = document.createElement("tr");
            tr.style.cursor = "pointer";
            tr.dataset.id = s.id;
            tr.dataset.lat = s.lat;
            tr.dataset.lng = s.lng;
            const badgeClass =
                s.status === "NEGERI"
                    ? "status-badge--negeri"
                    : "status-badge--swasta";
            tr.innerHTML = `
                <td><strong>${s.nama}</strong></td>
                <td><span class="status-badge ${badgeClass}">${s.status}</span></td>
                <td style="text-align:right;font-weight:600;">${s.murid.toLocaleString()}</td>
            `;
            tr.addEventListener("click", () => {
                openSchoolDetail(s);
            });
            fragment.appendChild(tr);
        }

        tbody.innerHTML = "";
        tbody.appendChild(fragment);

        document.getElementById("result-count").textContent = filtered.length;
        renderPagination(page, totalPages, paginate);
    }

    paginate(1);
}

function renderPagination(current, total, callback) {
    const container = document.getElementById("pagination");
    container.innerHTML = "";

    const fragment = document.createDocumentFragment();

    const prevBtn = document.createElement("button");
    prevBtn.textContent = "\u2190";
    prevBtn.disabled = current <= 1;
    prevBtn.addEventListener("click", () => callback(current - 1));
    fragment.appendChild(prevBtn);

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
        fragment.appendChild(firstBtn);
        if (startPage > 2) {
            const dots = document.createElement("span");
            dots.textContent = "...";
            dots.className = "pagination__dots";
            fragment.appendChild(dots);
        }
    }

    for (let i = startPage; i <= endPage; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        if (i === current) btn.classList.add("active");
        btn.addEventListener("click", () => callback(i));
        fragment.appendChild(btn);
    }

    if (endPage < total) {
        if (endPage < total - 1) {
            const dots = document.createElement("span");
            dots.textContent = "...";
            dots.className = "pagination__dots";
            fragment.appendChild(dots);
        }
        const lastBtn = document.createElement("button");
        lastBtn.textContent = total;
        lastBtn.addEventListener("click", () => callback(total));
        fragment.appendChild(lastBtn);
    }

    const nextBtn = document.createElement("button");
    nextBtn.textContent = "\u2192";
    nextBtn.disabled = current >= total;
    nextBtn.addEventListener("click", () => callback(current + 1));
    fragment.appendChild(nextBtn);

    const info = document.createElement("span");
    info.className = "pagination__info";
    info.textContent = `Halaman ${current} dari ${total}`;
    fragment.appendChild(info);

    container.appendChild(fragment);
}

function setupTableSearch() {
    const searchInput = document.getElementById("table-search");
    searchInput.addEventListener(
        "input",
        debounce(() => {
            const filtered = filterSchools(currentFilters);
            renderTable(filtered);
        }, SEARCH_DEBOUNCE_MS),
    );
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

    const resultCount = document.getElementById("result-count");
    if (resultCount) resultCount.textContent = "0";

    populateSelect(
        "filter-jenjang",
        ["KB", "TK", "SD", "SMP", "SMA/SMK", "Semua"],
        "Pilih Jenjang",
    );
    populateSelect(
        "filter-status",
        ["NEGERI", "SWASTA", "Semua"],
        "Pilih Status",
    );

    let pulauNames = [];
    try {
        pulauNames = await fetchPulau();
    } catch (err) {
        console.error(
            "[SatuPeta] Gagal memuat daftar pulau:",
            err.message || err,
        );
    }
    populateSelect("filter-pulau", pulauNames, "Pilih Pulau");
    populateSelect("filter-provinsi", [], "Pilih Provinsi");
    populateSelect("filter-kabupaten", [], "Pilih Kabupaten/Kota");
    populateSelect("filter-kecamatan", [], "Pilih Kecamatan");
    setSelectEnabled("filter-provinsi", false);
    setSelectEnabled("filter-kabupaten", false);
    setSelectEnabled("filter-kecamatan", false);

    let pulauRequestSeq = 0;
    tomSelectInstances["filter-pulau"].on("change", async function (value) {
        const seq = ++pulauRequestSeq;
        _cascadeSeq++;
        let provs = [];
        if (value) {
            try {
                provs = await fetchProvinsi(value);
            } catch (err) {
                console.error(
                    "[SatuPeta] Gagal memuat provinsi untuk pulau:",
                    err.message || err,
                );
                provs = [];
            }
        }
        if (seq !== pulauRequestSeq) return;
        repopulateTomSelect("filter-provinsi", provs, "Pilih Provinsi");
        repopulateTomSelect("filter-kabupaten", [], "Pilih Kabupaten/Kota");
        repopulateTomSelect("filter-kecamatan", [], "Pilih Kecamatan");
        setSelectEnabled("filter-provinsi", !!value && provs.length > 0);
        setSelectEnabled("filter-kabupaten", false);
        setSelectEnabled("filter-kecamatan", false);
    });

    tomSelectInstances["filter-provinsi"].on("change", function (value) {
        loadKabupatenOptions(value);
    });

    tomSelectInstances["filter-kabupaten"].on("change", function (value) {
        loadKecamatanOptions(value);
    });

    document
        .getElementById("btn-terapkan")
        .addEventListener("click", applyFilters);
    document
        .getElementById("btn-reset")
        .addEventListener("click", resetFilters);

    setupTableSearch();
    setupPanelClose();

    setSidebarState("default");

    schools = [];
    renderAggregatedMarkers(schools);
    updateStatCards(schools);
    updateLegend("Semua");
    renderTable(schools);

    fetchAndRenderSummary();

    // --- Koreksi Modal ---
    const koreksiModal = document.getElementById("koreksiModal");
    const koreksiForm = document.getElementById("formKoreksi");
    const btnKoreksi = document.getElementById("btn-koreksi");

    if (btnKoreksi) {
        btnKoreksi.addEventListener("click", () => {
            const npsnInput = document.getElementById("modal_sekolah_npsn");
            if (npsnInput) npsnInput.value = _currentSchoolNpsn || "";
            koreksiModal.classList.remove("hidden");
        });
    }

    if (koreksiForm) {
        koreksiForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(koreksiForm);
            const submitBtn = koreksiModal.querySelector(
                'button[type="submit"]',
            );
            if (submitBtn) submitBtn.disabled = true;

            try {
                const res = await fetch(koreksiForm.action, {
                    method: "POST",
                    body: formData,
                    headers: { "X-Requested-With": "XMLHttpRequest" },
                });

                if (res.ok) {
                    koreksiModal.classList.add("hidden");
                    koreksiForm.reset();
                    showToast(
                        "Laporan berhasil dikirim dan akan segera ditinjau.",
                        "success",
                    );
                } else if (res.status === 422) {
                    const data = await res.json().catch(() => ({}));
                    const errors = data.errors;
                    let msg = "Data yang dikirim tidak valid.";
                    if (errors) {
                        const firstKey = Object.keys(errors)[0];
                        msg = errors[firstKey][0];
                    } else if (data.message) {
                        msg = data.message;
                    }
                    showToast(msg, "error");
                } else if (res.status === 429) {
                    const retryAfter = res.headers.get("Retry-After");
                    const msg = retryAfter
                        ? `Terlalu banyak permintaan. Coba lagi dalam ${retryAfter} detik.`
                        : "Terlalu banyak permintaan. Silakan tunggu sebentar lalu coba lagi.";
                    showToast(msg, "error");
                } else if (res.status === 401 || res.status === 419) {
                    showToast(
                        "Sesi Anda telah berakhir. Mengalihkan ke halaman login...",
                        "error",
                    );
                    setTimeout(() => {
                        window.location.href = "/login";
                    }, 2500);
                    return;
                } else {
                    const data = await res.json().catch(() => ({}));
                    const msg =
                        data.message || "Terjadi kesalahan. Silakan coba lagi.";
                    showToast(msg, "error");
                }
            } catch (err) {
                showToast(
                    "Gagal mengirim laporan. Periksa koneksi Anda.",
                    "error",
                );
            } finally {
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    }

    document.addEventListener("keydown", (e) => {
        if (
            e.key === "Escape" &&
            koreksiModal &&
            !koreksiModal.classList.contains("hidden")
        ) {
            koreksiModal.classList.add("hidden");
        }
    });
});

function showToast(message, type) {
    const existing = document.getElementById("koreksiToast");
    if (existing) existing.remove();

    const bg = type === "success" ? "bg-green-500" : "bg-red-500";
    const icon =
        type === "success"
            ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>'
            : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';

    const toast = document.createElement("div");
    toast.id = "koreksiToast";
    toast.className = `fixed top-6 right-6 z-[10001] ${bg} text-white px-6 py-3 rounded-lg shadow-lg font-medium text-sm flex items-center gap-2 transition-opacity duration-300`;
    toast.innerHTML = `${icon} ${message}`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}
