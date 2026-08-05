// Shared cascading region dropdown module — Pulau → Provinsi → Kabupaten → Kecamatan.
// Data source: internal ref API (/api/ref/*), zero egress, cached server-side.
// Exposed globally (window.initWilayahCascade + window.wilayahRefApi) for use by
// inline Blade scripts, and exported for bundler import (dashboard.js).

const API_BASE = "/api/ref";

async function apiGet(path, params = {}) {
    const qs = new URLSearchParams();
    for (const [key, value] of Object.entries(params)) {
        if (value !== undefined && value !== null && value !== "") {
            qs.set(key, value);
        }
    }
    const suffix = qs.toString() ? `?${qs.toString()}` : "";
    const res = await fetch(`${API_BASE}${path}${suffix}`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();
    return Array.isArray(json) ? json : [];
}

export async function fetchPulau() {
    return (await apiGet("/pulau")).map((row) => row.nama);
}

export async function fetchProvinsi(pulau = "") {
    return (await apiGet("/provinsi", { pulau })).map((row) => row.nama);
}

export async function fetchKabupaten(provinsi) {
    return (await apiGet("/kabupaten", { provinsi })).map((row) => row.nama);
}

export async function fetchKecamatan(kabupaten) {
    return (await apiGet("/kecamatan", { kabupaten })).map((row) => row.nama);
}

function toElement(selector) {
    if (typeof selector === "string") return document.querySelector(selector);
    return selector;
}

function makeOption(value) {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = value;
    return option;
}

function setOptions(select, names, placeholder) {
    if (!select) return;
    select.innerHTML = "";
    const placeholderOption = makeOption("");
    placeholderOption.disabled = true;
    placeholderOption.selected = true;
    placeholderOption.textContent = placeholder || "Pilih";
    select.appendChild(placeholderOption);
    names.forEach((name) => select.appendChild(makeOption(name)));
}

/**
 * Inisialisasi dropdown wilayah bertingkat untuk `<select>` polos.
 *
 * config:
 *   provinsi / kabupaten / kecamatan — elemen select atau selector CSS (wajib)
 *   pulau                            — elemen select/selector opsional (level tertinggi)
 *   placeholders                     — { pulau, provinsi, kabupaten, kecamatan }
 *   initial                          — { pulau, provinsi, kabupaten, kecamatan }
 *   scopePulau                       — batasi ke satu pulau (mis. "Sumatera") tanpa select pulau
 *   fetchProvinsi / fetchKabupaten / fetchKecamatan — override opsional
 *   onChange                         — (values) => void
 *
 * Mengembalikan controller { getValues, setValues } untuk prefill ulang (mode edit).
 */
export function initWilayahCascade(config = {}) {
    const pulauSelect = toElement(config.pulau);
    const provinsiSelect = toElement(config.provinsi);
    const kabupatenSelect = toElement(config.kabupaten);
    const kecamatanSelect = toElement(config.kecamatan);

    if (!provinsiSelect || !kabupatenSelect || !kecamatanSelect) {
        throw new Error("initWilayahCascade: select provinsi, kabupaten, dan kecamatan wajib ada.");
    }

    const placeholders = {
        pulau: config.placeholders?.pulau || "Pilih Pulau",
        provinsi: config.placeholders?.provinsi || "Pilih Provinsi",
        kabupaten: config.placeholders?.kabupaten || "Pilih Kabupaten/Kota",
        kecamatan: config.placeholders?.kecamatan || "Pilih Kecamatan",
    };

    const scopePulau = config.scopePulau || null;
    const state = {
        pulau: config.initial?.pulau || scopePulau || "",
        provinsi: config.initial?.provinsi || "",
        kabupaten: config.initial?.kabupaten || "",
        kecamatan: config.initial?.kecamatan || "",
    };

    let sequence = 0;

    function fresh() {
        const current = ++sequence;
        return () => current === sequence;
    }

    function getValues() {
        return { ...state };
    }

    function notify() {
        if (typeof config.onChange === "function") config.onChange(getValues());
    }

    async function loadProvinsi() {
        const isFresh = fresh();
        let names = [];
        try {
            names = config.fetchProvinsi
                ? await config.fetchProvinsi(state.pulau)
                : await fetchProvinsi(state.pulau);
        } catch (error) {
            console.error("[wilayah] Gagal memuat daftar provinsi:", error);
        }
        if (!isFresh()) return;

        setOptions(provinsiSelect, names, placeholders.provinsi);
        provinsiSelect.disabled = names.length === 0;
        if (state.provinsi && names.includes(state.provinsi)) {
            provinsiSelect.value = state.provinsi;
        }
        await loadKabupaten();
    }

    async function loadKabupaten() {
        const isFresh = fresh();
        let names = [];
        if (state.provinsi) {
            try {
                names = config.fetchKabupaten
                    ? await config.fetchKabupaten(state.provinsi)
                    : await fetchKabupaten(state.provinsi);
            } catch (error) {
                console.error("[wilayah] Gagal memuat daftar kabupaten/kota:", error);
            }
        }
        if (!isFresh()) return;

        setOptions(kabupatenSelect, names, placeholders.kabupaten);
        kabupatenSelect.disabled = names.length === 0;
        if (state.kabupaten && names.includes(state.kabupaten)) {
            kabupatenSelect.value = state.kabupaten;
        }
        await loadKecamatan();
    }

    async function loadKecamatan() {
        const isFresh = fresh();
        let names = [];
        if (state.kabupaten) {
            try {
                names = config.fetchKecamatan
                    ? await config.fetchKecamatan(state.kabupaten)
                    : await fetchKecamatan(state.kabupaten);
            } catch (error) {
                console.error("[wilayah] Gagal memuat daftar kecamatan:", error);
            }
        }
        if (!isFresh()) return;

        setOptions(kecamatanSelect, names, placeholders.kecamatan);
        kecamatanSelect.disabled = names.length === 0;
        if (state.kecamatan && names.includes(state.kecamatan)) {
            kecamatanSelect.value = state.kecamatan;
        }
        notify();
    }

    async function loadPulau() {
        const isFresh = fresh();
        let names = [];
        try {
            names = await fetchPulau();
        } catch (error) {
            console.error("[wilayah] Gagal memuat daftar pulau:", error);
        }
        if (!isFresh()) return;

        setOptions(pulauSelect, names, placeholders.pulau);
        if (state.pulau && names.includes(state.pulau)) {
            pulauSelect.value = state.pulau;
        }
        await loadProvinsi();
    }

    function handlePulauChange() {
        state.pulau = pulauSelect ? pulauSelect.value : scopePulau || "";
        state.provinsi = "";
        state.kabupaten = "";
        state.kecamatan = "";
        loadProvinsi();
        notify();
    }

    function handleProvinsiChange() {
        state.provinsi = provinsiSelect ? provinsiSelect.value : "";
        state.kabupaten = "";
        state.kecamatan = "";
        loadKabupaten();
        notify();
    }

    function handleKabupatenChange() {
        state.kabupaten = kabupatenSelect ? kabupatenSelect.value : "";
        state.kecamatan = "";
        loadKecamatan();
        notify();
    }

    function handleKecamatanChange() {
        state.kecamatan = kecamatanSelect ? kecamatanSelect.value : "";
        notify();
    }

    function init() {
        if (pulauSelect) {
            loadPulau();
        } else {
            loadProvinsi();
        }
    }

    if (pulauSelect) pulauSelect.addEventListener("change", handlePulauChange);
    provinsiSelect.addEventListener("change", handleProvinsiChange);
    kabupatenSelect.addEventListener("change", handleKabupatenChange);
    kecamatanSelect.addEventListener("change", handleKecamatanChange);

    init();

    return {
        getValues,
        setValues(values = {}) {
                state.pulau = values.pulau ?? scopePulau ?? "";
            state.provinsi = values.provinsi || "";
            state.kabupaten = values.kabupaten || "";
            state.kecamatan = values.kecamatan || "";
            if (pulauSelect) pulauSelect.value = state.pulau;
            init();
        },
    };
}

if (typeof window !== "undefined") {
    window.wilayahRefApi = { fetchPulau, fetchProvinsi, fetchKabupaten, fetchKecamatan };
    window.initWilayahCascade = initWilayahCascade;
}
