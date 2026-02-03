// --- Inisialisasi Peta ---
var mymap = L.map("map", {
    zoomControl: false,
}).setView([-7.2098686, 108.237827], 9);

// --- Layer dasar ---
const baseMaps = {
    "Esri Satellite": L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
        { attribution: "&copy; Esri &mdash; Source: Esri, USGS, etc." },
    ),
    "Google Streets": L.tileLayer(
        "http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}",
        { maxZoom: 20, subdomains: ["mt0", "mt1", "mt2", "mt3"] },
    ),
    "Google Hybrid": L.tileLayer(
        "http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}",
        { maxZoom: 20, subdomains: ["mt0", "mt1", "mt2", "mt3"] },
    ),
    "Google Satellite": L.tileLayer(
        "http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",
        { maxZoom: 20, subdomains: ["mt0", "mt1", "mt2", "mt3"] },
    ),
    "Google Terrain": L.tileLayer(
        "http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}",
        { maxZoom: 20, subdomains: ["mt0", "mt1", "mt2", "mt3"] },
    ),
};
baseMaps["Google Satellite"].addTo(mymap);

// kontrol layer & zoom
L.control
    .layers(baseMaps, null, { position: "topright", collapsed: false })
    .addTo(mymap);
L.control.zoom({ position: "bottomright" }).addTo(mymap);

// --- Marker icons ---
function getColorByJenis(jenis) {
    switch ((jenis || "").toLowerCase()) {
        case "embung":
            return "#0d6efd";
        case "bendung":
            return "#6c757d";
        case "bendungan":
            return "#0dcaf0";
        case "pengaman pantai":
            return "#6f42c1";
        case "pengendali sedimen":
            return "#9c956d";
        case "pengendali banjir":
            return "#d63384";
        default:
            return "#212529";
    }
}

function createColoredIcon(color, label = "", borderColor = "#ffffff") {
    return L.divIcon({
        className: "custom-marker",
        html: `<div style="
            background:${color};
            color:#fff;
            font-size:11px;
            font-weight:bold;
            text-align:center;
            line-height:26px;
            width:26px;
            height:26px;
            border-radius:50%;
            border:3px solid ${borderColor};
            box-shadow:0 0 3px rgba(0,0,0,0.5);
        ">${label}</div>`,
        iconSize: [26, 26],
        iconAnchor: [13, 26],
        popupAnchor: [0, -26],
    });
}

function getColorByAirBaku(jenis) {
    switch ((jenis || "").toLowerCase()) {
        case "sumur":
            return "#198754"; // hijau
        case "mata air":
            return "#20c997"; // teal
        case "intake sungai":
            return "#0dcaf0"; // cyan
        case "pah/absah":
            return "#ffc107"; // kuning
        case "tampungan air baku":
            return "#9f4951"; // merah
        default:
            return "#6c757d"; // abu
    }
}

// --- Border Color by Status Operasi ---
function getBorderColorByStatusOperasi(status) {
    if (!status) return "#ffffff"; // default putih
    switch (status.trim()) {
        case "Beroperasi":
            return "#198754"; // hijau
        case "Tidak Beroperasi":
            return "#dc3545"; // merah
        case "Dalam Pembangunan":
            return "#ffc107"; // kuning
        default:
            return "#ffffff"; // putih
    }
}

//icon seigita
function createTriangleIcon(color, size = 14) {
    return L.divIcon({
        className: "",
        html: `
            <div style="
                width:0;height:0;
                border-left:${size}px solid transparent;
                border-right:${size}px solid transparent;
                border-bottom:${size * 1.8}px solid ${color};
                filter: drop-shadow(0 0 2px rgba(0,0,0,0.5));
            "></div>
        `,
        iconSize: [size * 2, size * 2],
        iconAnchor: [size, size * 1.8],
        popupAnchor: [0, -size * 1.8],
    });
}

// mapping icon
const iconMap = {
    embung: createColoredIcon(getColorByJenis("embung"), "E"),
    bendung: createColoredIcon(getColorByJenis("bendung"), "B"),
    bendungan: createColoredIcon(getColorByJenis("bendungan"), "BD"),
    "pengendali banjir": createColoredIcon(
        getColorByJenis("pengendali banjir"),
        "PB",
    ),
    "pengaman pantai": createColoredIcon(
        getColorByJenis("pengaman pantai"),
        "PP",
    ),
    "pengendali sedimen": createColoredIcon(
        getColorByJenis("pengendali sedimen"),
        "PS",
    ),
};

// simpan data aset supaya tidak fetch ulang
var markersAset = {};
var markersBM = {};
var markerAirbaku = {};
var cacheAset = {};
var cacheBM = {};
var cacheAirbaku = {};

// --- Layer khusus untuk pencarian ---
var searchLayer = L.layerGroup().addTo(mymap);

// clear marker
function clearMarkers(storage, jenis) {
    if (storage[jenis]) {
        storage[jenis].forEach((m) => {
            mymap.removeLayer(m);
            searchLayer.removeLayer(m);
        });
        storage[jenis] = [];
    }
}

// --- Border Color by Kondisi ---
function getBorderColorByKondisi(kondisi) {
    if (!kondisi) return "#ffffff"; // default putih
    switch (kondisi.trim()) {
        case "Baik / Beroperasi":
            return "#198754"; // hijau bootstrap success
        case "Rusak Ringan":
            return "#ffc107"; // kuning bootstrap warning
        case "Rusak Berat":
            return "#dc3545"; // merah bootstrap danger
        case "Hilang":
            return "#212529"; // hitam bootstrap dark
        default:
            return "#ffffff"; // putih default
    }
}

// fungsi render marker dari data cache
function renderAset(jenis, data) {
    clearMarkers(markersAset, jenis);
    markersAset[jenis] = [];

    data.forEach((item) => {
        const iconKey = (item.jenis_aset || "").toLowerCase();
        // Ambil kondisi dari infrastruktur dulu, kalau kosong pakai bangunan
        const kondisiFinal =
            item.kondisi_infrastruktur &&
            item.kondisi_infrastruktur.trim() !== ""
                ? item.kondisi_infrastruktur
                : item.kondisi_bangunan;
        const borderColor = getBorderColorByKondisi(kondisiFinal);
        const marker = L.marker([parseFloat(item.lat), parseFloat(item.long)], {
            icon: createColoredIcon(
                getColorByJenis(iconKey),
                iconKey.substring(0, 2).toUpperCase(), // label 2 huruf
                borderColor,
            ),
        }).addTo(mymap);

        function getKondisiBadge(kondisi) {
            if (!kondisi || kondisi.trim() === "") return `-`;
            switch (kondisi.trim()) {
                case "Baik / Beroperasi":
                    return `<span class="badge bg-success">${kondisi}</span>`;
                case "Rusak Ringan":
                    return `<span class="badge bg-warning text-dark">${kondisi}</span>`;
                case "Rusak Berat":
                    return `<span class="badge bg-danger">${kondisi}</span>`;
                case "Hilang":
                    return `<span class="badge bg-dark text-white">${kondisi}</span>`;
                default:
                    return kondisi;
            }
        }

        marker.bindPopup(`
            <div class="card shadow-sm border-0" style="width:320px;font-size:0.85rem;border-radius:12px;overflow:hidden;">
                <div class="card-header bg-primary text-white py-2 px-3">
                    <div class="fw-bold">${item.nama_aset}</div>
                    <small class="text-light">${item.jenis_aset}</small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tr><th style="width:40%;">WS</th><td>${
                            item.wilayah_sungai
                        }</td></tr>
                        <tr><th>DAS</th><td>${item.das}</td></tr>
                        <tr><th>Koordinat</th><td>${item.lat}, ${
                            item.long
                        }</td></tr>
                        <tr><th>Lokasi</th><td>${item.village}, ${
                            item.district
                        }, ${item.city}</td></tr>
                        <tr><th>Kondisi</th><td>${getKondisiBadge(
                            kondisiFinal,
                        )}</td></tr>
                    </table>
                </div>
                <div class="card-footer bg-light p-2">
                    <button class="btn btn-sm btn-primary" onclick="detailAset(${
                        item.id
                    })">
                        <i class="fa fa-info-circle me-1"></i> Detail
                    </button>

                    <a class="btn btn-sm btn-dark text-white" href="https://www.google.com/maps/dir/?api=1&destination=${item.lat},${item.long}" target="_blank">
                        <i class="fa fa-location-arrow me-1"></i> Petunjuk Arah
                    </a>
                </div>
            </div>
        `);

        markersAset[jenis].push(marker);
        marker.feature = {
            properties: {
                name: item.nama_aset,
                jenis_aset: item.jenis_aset,
                lat: item.lat,
                lng: item.long,
                searchText: `${item.nama_aset} ${item.lat} ${item.long}`,
            },
        };
        searchLayer.addLayer(marker);

        searchLayer.addLayer(marker);
    });
}

function renderBM(jenis, data) {
    clearMarkers(markersBM, jenis);
    markersBM[jenis] = [];

    data.forEach((item) => {
        const iconKey = (item.jenis_pekerjaan || "").toLowerCase();
        const marker = L.marker([parseFloat(item.lat), parseFloat(item.long)], {
            icon: iconMap[iconKey] || new L.Icon.Default(),
        }).addTo(mymap);

        marker.bindPopup(`
            <div class="card shadow-sm border-0" style="width:320px;font-size:0.85rem;border-radius:12px;overflow:hidden;">
                <div class="card-header bg-warning text-white py-2 px-3">
                    <div class="fw-bold">${item.nama_pekerjaan}</div>
                    <small class="text-light">${item.jenis_pekerjaan}</small>
                </div>
                <div class="card-body p-3">
                    <div><b>Kode BM:</b> ${item.kode_bm}</div>
                    <div><b>No. Registrasi:</b> ${item.no_registrasi}</div>
                    <div><b>Lokasi:</b> ${item.village}, ${item.district}, ${item.city}</div>
                </div>
                <div class="card-footer bg-light p-2">
                    <button class="btn btn-sm btn-warning w-100" onclick="detailbm(${item.id})">
                        <i class="fa fa-info-circle me-1"></i> Detail
                    </button>
                </div>
            </div>
        `);

        markersBM[jenis].push(marker);
        marker.feature = { properties: { name: item.nama_pekerjaan } };
        searchLayer.addLayer(marker);
    });
}

// --- Render Air Baku ---
function renderAirbaku(jenis, data) {
    clearMarkers(markerAirbaku, jenis);
    markerAirbaku[jenis] = [];

    data.forEach((item) => {
        const iconKey = (item.jenis_aset || "").toLowerCase();
        const color = getColorByAirBaku(iconKey);
        const borderColor = getBorderColorByStatusOperasi(item.status_operasi);

        // Label otomatis: ambil huruf pertama tiap kata
        const label = (item.jenis_aset || "")
            .split(" ")
            .map((w) => w[0])
            .join("")
            .substring(0, 2)
            .toUpperCase();

        const marker = L.marker([parseFloat(item.lat), parseFloat(item.long)], {
            icon: createColoredIcon(color, label, borderColor),
        }).addTo(mymap);

        function getStatusOperasiBadge(status) {
            if (!status || status.trim() === "") return `-`;
            switch (status.trim()) {
                case "Beroperasi":
                    return `<span class="badge bg-success">${status}</span>`;
                case "Tidak Beroperasi":
                    return `<span class="badge bg-danger">${status}</span>`;
                default:
                    return status;
            }
        }

        marker.bindPopup(`
            <div class="card shadow-sm border-0" style="width:320px;font-size:0.85rem;border-radius:12px;overflow:hidden;">
                <div class="card-header bg-info text-white py-2 px-3">
                    <div class="fw-bold">${item.nama_aset ?? "-"}</div>
                    <small class="text-light">${item.jenis_aset ?? "-"}</small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tr><th style="width:40%;">WS</th><td>${
                            item.wilayah_sungai ?? "-"
                        }</td></tr>
                        <tr><th>DAS</th><td>${item.das ?? "-"}</td></tr>
                        <tr><th>Koordinat</th><td>${item.lat}, ${
                            item.long
                        }</td></tr>
                        <tr><th>Lokasi</th><td>${item.village ?? "-"}, ${
                            item.district ?? "-"
                        }, ${item.city ?? "-"}</td></tr>
                        <tr><th>Tahun</th><td>${
                            item.tahun_pembangunan ?? "-"
                        }</td></tr>
                        <tr><th>Status Operasi</th><td>
                            ${getStatusOperasiBadge(
                                item.status_operasi ?? "-",
                            )}</td></tr>
                        <tr><th>Status Pekerjaan</th><td>${
                            item.status_pekerjaan ?? "-"
                        }</td></tr>
                    </table>
                </div>
                <div class="card-footer bg-light p-2">
                    <button class="btn btn-sm btn-info " onclick="detailAirbaku(${
                        item.id
                    })">
                        <i class="fa fa-info-circle me-1"></i> Detail
                    </button>

                    <a class="btn btn-sm btn-dark text-white" href="https://www.google.com/maps/dir/?api=1&destination=${item.lat},${item.long}" target="_blank">
                        <i class="fa fa-location-arrow me-1"></i> Petunjuk Arah
                    </a>
                </div>
            </div>
        `);

        markerAirbaku[jenis].push(marker);
        marker.feature = {
            properties: {
                name: item.nama_aset,
                jenis_aset: item.jenis_aset,
                lat: item.lat,
                lng: item.long,
                searchText: `${item.nama_aset} ${item.lat} ${item.long}`,
            },
        };
        searchLayer.addLayer(marker);

        searchLayer.addLayer(marker);
    });
}
// --- Fetch Data Aset dengan cache ---
async function fetchDataAset() {
    const selected = [];
    document.querySelectorAll(".jenis-aset").forEach((cb) => {
        if (cb.checked) {
            selected.push(cb.value);
        } else {
            clearMarkers(markersAset, cb.value);
        }
    });

    for (const jenis of selected) {
        if (cacheAset[jenis]) {
            renderAset(jenis, cacheAset[jenis]);
        } else {
            const urlParams = new URL(urlAsset);
            urlParams.searchParams.append("jenis_aset[]", jenis);
            try {
                const res = await fetch(urlParams);
                const data = await res.json();
                cacheAset[jenis] = data; // simpan ke cache
                renderAset(jenis, data);
            } catch (err) {
                console.error("Error Aset:", err.message);
            }
        }
    }
}

// --- Fetch Data BM dengan cache ---
async function fetchDataBM() {
    const selected = [];
    document.querySelectorAll(".jenis-benchmark").forEach((cb) => {
        if (cb.checked) {
            selected.push(cb.value);
        } else {
            clearMarkers(markersBM, cb.value);
        }
    });

    for (const jenis of selected) {
        if (cacheBM[jenis]) {
            renderBM(jenis, cacheBM[jenis]);
        } else {
            const urlParams = new URL(urlBM);
            urlParams.searchParams.append("jenis_pekerjaan[]", jenis);
            try {
                const res = await fetch(urlParams);
                const data = await res.json();
                cacheBM[jenis] = data; // simpan ke cache
                renderBM(jenis, data);
            } catch (err) {
                console.error("Error BM:", err.message);
            }
        }
    }
}

async function fetchDataAirbaku() {
    const selected = [];
    document.querySelectorAll(".jenis-air-baku").forEach((cb) => {
        if (cb.checked) {
            selected.push(cb.value);
        } else {
            clearMarkers(markerAirbaku, cb.value);
        }
    });

    for (const jenis of selected) {
        if (cacheAirbaku[jenis]) {
            renderAirbaku(jenis, cacheAirbaku[jenis]);
        } else {
            const urlParams = new URL(urlAirbaku);
            urlParams.searchParams.append("jenis_aset[]", jenis);
            try {
                const res = await fetch(urlParams);
                const data = await res.json();
                cacheAirbaku[jenis] = data;
                renderAirbaku(jenis, data);
            } catch (err) {
                console.error("Error Airbaku:", err.message);
            }
        }
    }
}

// --- Event listener ---
const spinner = document.getElementById("spinner");
document.querySelectorAll(".jenis-aset").forEach((cb) => {
    cb.addEventListener("change", () => {
        spinner.style.display = "block";
        fetchDataAset().finally(() => (spinner.style.display = "none"));
    });
});
document.querySelectorAll(".jenis-benchmark").forEach((cb) => {
    cb.addEventListener("change", () => {
        spinner.style.display = "block";
        fetchDataBM().finally(() => (spinner.style.display = "none"));
    });
});

document.querySelectorAll(".jenis-air-baku").forEach((cb) => {
    cb.addEventListener("change", () => {
        spinner.style.display = "block";
        fetchDataAirbaku().finally(() => (spinner.style.display = "none"));
    });
});

// ini untuk geojson sungai
// 🔹 Fungsi untuk menentukan warna berdasarkan ORDE
function getSungaiColor(orde) {
    switch (parseInt(orde)) {
        case 1:
            return "#1e88e5"; // biru tua
        case 2:
            return "#4caf50"; // hijau
        case 3:
            return "#fdd835"; // kuning
        case 4:
            return "#fb8c00"; // oranye
        case 5:
            return "#e53935"; // merah
        case 6:
            return "#9c27b0"; // ungu
        default:
            return "#9e9e9e"; // abu-abu jika tidak dikenal
    }
}

let sungaiLayer; // untuk menyimpan layer sungai
let allSungaiData = null;

// 1️⃣ Ambil data GeoJSON sekali saja
fetch("js/sungai.geojson")
    .then((res) => res.json())
    .then((data) => {
        allSungaiData = data;
        //console.log("Data sungai loaded:", allSungaiData);
    });

var searchLayerSungai = L.layerGroup().addTo(mymap);

// 2️⃣ Fungsi untuk menggambar sungai berdasarkan orde terpilih
function updateSungaiLayer(selectedOrdes) {
    if (!allSungaiData) return;

    // hapus layer lama
    if (sungaiLayer) mymap.removeLayer(sungaiLayer);

    // hapus semua layer dari searchLayer
    //searchLayer.clearLayers();
    searchLayerSungai.clearLayers();

    // filter fitur berdasarkan orde yang dipilih
    const filteredFeatures = allSungaiData.features.filter((f) => {
        const orde = f.properties?.ORDE;
        return (
            selectedOrdes.includes("all") ||
            selectedOrdes.includes(orde.toString())
        );
    });

    // buat layer baru
    sungaiLayer = L.geoJSON(
        { type: "FeatureCollection", features: filteredFeatures },
        {
            style: (feature) => {
                const orde = feature.properties?.ORDE;
                return {
                    color: getSungaiColor(orde),
                    weight: orde === 1 ? 4 : 2.5,
                    opacity: 0.85,
                };
            },
            onEachFeature: (feature, layer) => {
                const p = feature.properties;

                const popupContent = `
                <div class="table-responsive" style="font-size:13px;">
                    <table class="table table-sm table-bordered mb-0">
                        <tr><th style="width:40%;">ORDE</th><td>${
                            p.ORDE || "-"
                        }</td></tr>
                        <tr><th>Nama Objek</th><td>${p.NAMOBJ || "-"}</td></tr>
                        <tr><th>Remark</th><td>${p.REMARK || "-"}</td></tr>
                        <tr><th>Layer</th><td>${p.layer || "-"}</td></tr>
                        <tr><th>Panjang</th><td>${p.Panjang || "-"} m</td></tr>
                        <tr><th>DAS</th><td>${p.DAS || "-"}</td></tr>
                    </table>
                </div>
                `;

                layer.bindPopup(popupContent);

                // 💡 Tambahkan properti searchable
                layer.feature = {
                    properties: { name: p.NAMOBJ || "Sungai Tanpa Nama" },
                };

                // 💡 Masukkan ke search layer
                //searchLayer.addLayer(layer);
                searchLayerSungai.addLayer(layer);
            },
        },
    ).addTo(mymap);
}

// 3️⃣ Event listener untuk semua checkbox sungai
document.querySelectorAll(".sungai-filter").forEach((cb) => {
    cb.addEventListener("change", () => {
        const allCb = document.getElementById("sungai-all");

        if (cb.value === "all" && cb.checked) {
            // ✅ Jika "All" dicentang → centang semua
            document
                .querySelectorAll(".sungai-filter")
                .forEach((el) => (el.checked = true));
        } else if (cb.value !== "all") {
            // ❌ Jika salah satu orde diubah → hapus centang "All"
            allCb.checked = false;
        }

        // Ambil semua yang dicentang untuk update peta
        const checked = Array.from(
            document.querySelectorAll(".sungai-filter:checked"),
        ).map((el) => el.value);

        updateSungaiLayer(checked.length ? checked : []);
    });
});

//irigasi layer
let irigasiLayers = {};
let irigasiDataCache = {};

async function loadIrigasiArea(key, file, color = "#0d6efd") {
    // kalau sudah pernah diload → langsung render
    if (irigasiDataCache[key]) {
        drawIrigasiLayer(key, irigasiDataCache[key], color);
        return;
    }

    try {
        const res = await fetch(file);
        const geojson = await res.json();
        irigasiDataCache[key] = geojson;
        drawIrigasiLayer(key, geojson, color);
    } catch (err) {
        console.error("Gagal load irigasi:", key, err);
    }
}

function drawIrigasiLayer(key, geojson, color) {
    // hapus dulu kalau ada
    if (irigasiLayers[key]) {
        mymap.removeLayer(irigasiLayers[key]);
    }

    irigasiLayers[key] = L.geoJSON(geojson, {
        style: {
            color: color,
            weight: 2,
            fillOpacity: 0.15,
        },
        onEachFeature: (feature, layer) => {
            const p = feature.properties || {};

            const luas = p.L_Baku
                ? Number(p.L_Baku).toLocaleString("id-ID", {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2,
                  }) + " Ha"
                : "-";

            layer.bindPopup(`
        <div style="font-size:13px; line-height:1.4">
            <div class="fw-bold mb-1 text-primary">
                ${p.Nm_Inf ?? "Daerah Irigasi"}
            </div>

            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td>Jenis</td>
                    <td>: ${p.Jenis_DI ?? "-"}</td>
                </tr>
                <tr>
                    <td>Luas Baku</td>
                    <td>: ${luas}</td>
                </tr>
                <tr>
                    <td>Kewenangan</td>
                    <td>: ${p.Kewenangan ?? "-"}</td>
                </tr>
                <tr>
                    <td>Provinsi</td>
                    <td>: ${p.Provinsi ?? "-"}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>: ${p.Status ?? "-"}</td>
                </tr>
                <tr>
                    <td>Tahun Data</td>
                    <td>: ${p.Thn_Dat ?? "-"}</td>
                </tr>
            </table>
        </div>
    `);

            // optional: masuk ke search control
            layer.feature = {
                properties: {
                    name: p.Nm_Inf ?? "Daerah Irigasi",
                },
            };
            searchLayer.addLayer(layer);
        },
    }).addTo(mymap);

    // zoom ke area (opsional, hanya saat pertama ON)
    mymap.fitBounds(irigasiLayers[key].getBounds());
}

document
    .getElementById("irigasi-cikunten-1")
    .addEventListener("change", function () {
        if (this.checked) {
            loadIrigasiArea(
                "area_cikunten_1",
                "js/area_cikunten_1.geojson",
                "#ff6f00",
            );
        } else if (irigasiLayers["area_cikunten_1"]) {
            mymap.removeLayer(irigasiLayers["area_cikunten_1"]);
        }
    });

document
    .getElementById("irigasi-cikunten-2")
    .addEventListener("change", function () {
        if (this.checked) {
            loadIrigasiArea(
                "area_cikunten_2",
                "js/area_cikunten_2.geojson",
                "#00c853",
            );
        } else if (irigasiLayers["area_cikunten_2"]) {
            mymap.removeLayer(irigasiLayers["area_cikunten_2"]);
        }
    });

document.getElementById("irigasi-all").addEventListener("change", function () {
    const checked = this.checked;

    document.querySelectorAll(".irigasi-filter").forEach((cb) => {
        cb.checked = checked;
        cb.dispatchEvent(new Event("change"));
    });
});

//Bangunan CIkunten dan convert utm
proj4.defs(
    "EPSG:32749",
    "+proj=utm +zone=49 +south +datum=WGS84 +units=m +no_defs",
);

function convertGeoJSON_UTM49_to_LatLng(geojson) {
    geojson.features.forEach((f) => {
        if (!f.geometry) return;

        const g = f.geometry;
        const convert = (c) => proj4("EPSG:32749", "EPSG:4326", c);

        if (g.type === "Point") {
            g.coordinates = convert(g.coordinates);
        }

        if (g.type === "LineString") {
            g.coordinates = g.coordinates.map(convert);
        }

        if (g.type === "MultiLineString") {
            g.coordinates = g.coordinates.map((line) => line.map(convert));
        }

        if (g.type === "Polygon") {
            g.coordinates = g.coordinates.map((ring) => ring.map(convert));
        }

        if (g.type === "MultiPolygon") {
            g.coordinates = g.coordinates.map((poly) =>
                poly.map((ring) => ring.map(convert)),
            );
        }
    });

    return geojson;
}

let bangunanIrigasiLayer = null;
let bangunanIrigasiCache = null;

/* ===============================
   BAGUNGAN CIKUNTEN 1
================================ */

async function loadBangunanCikuntenI() {
    if (bangunanIrigasiLayer) {
        mymap.addLayer(bangunanIrigasiLayer);
        return;
    }

    const res = await fetch("js/bangunan_cikunten_1.geojson");
    let geojson = await res.json();

    geojson = convertGeoJSON_UTM49_to_LatLng(geojson);

    bangunanIrigasiLayer = L.geoJSON(geojson, {
        pointToLayer: (f, latlng) =>
            L.marker(latlng, {
                icon: createTriangleIcon("#e53935"), // 🔴 sesuai legend
            }),

        onEachFeature: (feature, layer) => {
            const p = feature.properties || {};

            // helper buat cek value valid
            const show = (label, value) => {
                if (
                    value === null ||
                    value === undefined ||
                    value === "" ||
                    value === 0 ||
                    value === "0"
                )
                    return "";
                return `<tr>
                    <td style="padding-right:6px;"><b>${label}</b></td>
                    <td>: ${value}</td>
                </tr>`;
            };

            const popupContent = `
                <div style="font-size:13px padding:4px;">
                    <div style="font-weight:bold;font-size:14px;margin-bottom:4px;">
                        ${p.NAMA ?? "Bangunan Irigasi"}
                    </div>

                    <table>
                        ${show("Nomenklatur", p.NOMENKLATU)}
                        ${show("Kode Bangunan", p.K_BANGUNAN)}
                        ${show("Urut", p.URUT)}
                        ${show("Jenis", p.JENIS)}
                        ${show("Kondisi", p.KONDISI)}
                        ${show("Fungsi", p.FUNGSI)}
                        ${show("Rotasi", p.ROTASI)}
                        ${show("Tahun", p.TAHUN)}
                    </table>
                </div>
            `;

            layer.bindPopup(popupContent);

            layer.feature = {
                properties: {
                    name: `${p.NAMA} ${p.NOMENKLATU}` ?? "",
                    searchText: `
                ${p.NAMA ?? ""}
                ${p.NOMENKLATU ?? ""}
                ${p.K_BANGUNAN ?? ""}
            `,
                },
            };

            // 🔍 WAJIB: masukin ke searchLayer
            searchLayer.addLayer(layer);
        },
    }).addTo(mymap);
}

document
    .getElementById("bangunan-cikunten-1")
    .addEventListener("change", function () {
        this.checked
            ? loadBangunanCikuntenI()
            : mymap.removeLayer(bangunanIrigasiLayer);
    });

/* ===============================
   BANGUNAN CIKUNTEN 2
================================ */

let bangunanIrigasiLayerCikunten2 = null;
async function loadBangunanCikuntenII() {
    if (bangunanIrigasiLayerCikunten2) {
        mymap.addLayer(bangunanIrigasiLayerCikunten2);
        return;
    }

    const res = await fetch("js/bangunan_cikunten_2.geojson");
    let geojson = await res.json();

    geojson = convertGeoJSON_UTM49_to_LatLng(geojson);

    bangunanIrigasiLayerCikunten2 = L.geoJSON(geojson, {
        pointToLayer: (f, latlng) =>
            L.marker(latlng, {
                icon: createTriangleIcon("#00acc1"), // 🟦 sesuai legend
            }),

        onEachFeature: (feature, layer) => {
            const p = feature.properties || {};

            // helper buat cek value valid
            const show = (label, value) => {
                if (
                    value === null ||
                    value === undefined ||
                    value === "" ||
                    value === 0 ||
                    value === "0"
                )
                    return "";
                return `<tr>
                    <td style="padding-right:6px;"><b>${label}</b></td>
                    <td>: ${value}</td>
                </tr>`;
            };

            const popupContent = `
                <div style="font-size:13px">
                    <div style="font-weight:bold;font-size:14px;margin-bottom:4px;">
                        ${p.NAMA ?? "Bangunan Irigasi"}
                    </div>

                    <table>
                        ${show("Nomenklatur", p.NOMENKLATU)}
                        ${show("Kode Bangunan", p.K_BANGUNAN)}
                        ${show("Urut", p.URUT)}
                        ${show("Jenis", p.JENIS)}
                        ${show("Kondisi", p.KONDISI)}
                        ${show("Fungsi", p.FUNGSI)}
                        ${show("Rotasi", p.ROTASI)}
                        ${show("Tahun", p.TAHUN)}
                    </table>
                </div>
            `;

            layer.bindPopup(popupContent);

            layer.feature = {
                properties: {
                    name: `${p.NAMA} ${p.NOMENKLATU}` ?? "",
                    searchText: `
                ${p.NAMA ?? ""}
                ${p.NOMENKLATU ?? ""}
                ${p.K_BANGUNAN ?? ""}
            `,
                },
            };

            // 🔍 WAJIB: masukin ke searchLayer
            searchLayer.addLayer(layer);
        },
    }).addTo(mymap);
}

document
    .getElementById("bangunan-cikunten-2")
    .addEventListener("change", function () {
        this.checked
            ? loadBangunanCikuntenII()
            : mymap.removeLayer(bangunanIrigasiLayerCikunten2);
    });

let jaringanCikunten1Layer = null;
let jaringanCikunten2Layer = null;

/* ===============================
   JARINGAN CIKUNTEN I
================================ */
async function loadJaringanCikuntenI() {
    if (jaringanCikunten1Layer) {
        mymap.addLayer(jaringanCikunten1Layer);
        return;
    }

    const res = await fetch("js/jaringan_cikunten_1.geojson");
    let geojson = await res.json();

    geojson = convertGeoJSON_UTM49_to_LatLng(geojson);

    jaringanCikunten1Layer = L.geoJSON(geojson, {
        style: {
            color: "#ffca28",
            weight: 3,
            opacity: 0.9,
        },
        onEachFeature: (feature, layer) => {
            const p = feature.properties || {};

            const show = (label, value) => {
                if (
                    value === null ||
                    value === undefined ||
                    value === "" ||
                    value === 0 ||
                    value === 0.0
                )
                    return "";
                return `
            <tr>
                <td style="padding-right:6px;"><b>${label}</b></td>
                <td>: ${value}</td>
            </tr>
        `;
            };

            const lengthMeter = p.length ? `${p.length.toFixed(2)} m` : null;

            const popupContent = `
                <div style="font-size:13px">
                    <div style="font-weight:bold;font-size:14px;margin-bottom:4px;">
                        ${p.NAMA ?? "Jaringan Irigasi"}
                    </div>

                    <table>
                        ${show("Nomenklatur", p.NOMENKLATU)}
                        ${show("Kode Saluran", p.K_SALURAN)}
                        ${show("Urut", p.URUT)}
                        ${show("Debit (Q)", p.Q)}
                        ${show("Luas (A)", p.A)}
                        ${show("Panjang", lengthMeter)}
                        ${show("Kondisi", p.KONDISI)}
                        ${show("Fungsi", p.FUNGSI)}
                        ${show("Tahun", p.TAHUN)}
                    </table>
                </div>
            `;

            layer.bindPopup(popupContent);

            layer.feature = {
                properties: {
                    name: `${p.NAMA} ${p.NOMENKLATU}` ?? "",
                    searchText: `
                ${p.NAMA ?? ""}
                ${p.NOMENKLATU ?? ""}
                ${p.K_BANGUNAN ?? ""}
            `,
                },
            };

            // 🔍 WAJIB: masukin ke searchLayer
            searchLayer.addLayer(layer);
        },
    }).addTo(mymap);
}

/* ===============================
   JARINGAN CIKUNTEN II
================================ */
async function loadJaringanCikuntenII() {
    if (jaringanCikunten2Layer) {
        mymap.addLayer(jaringanCikunten2Layer);
        return;
    }

    const res = await fetch("js/jaringan_cikunten_2.geojson");
    let geojson = await res.json();

    geojson = convertGeoJSON_UTM49_to_LatLng(geojson);

    jaringanCikunten2Layer = L.geoJSON(geojson, {
        style: {
            color: "#76ff03",
            weight: 3,
            opacity: 0.9,
        },
        onEachFeature: (feature, layer) => {
            const p = feature.properties || {};

            const show = (label, value) => {
                if (
                    value === null ||
                    value === undefined ||
                    value === "" ||
                    value === 0 ||
                    value === 0.0
                )
                    return "";
                return `
            <tr>
                <td style="padding-right:6px;"><b>${label}</b></td>
                <td>: ${value}</td>
            </tr>
        `;
            };

            const lengthMeter = p.length ? `${p.length.toFixed(2)} m` : null;

            const popupContent = `
                <div style="font-size:13px">
                    <div style="font-weight:bold;font-size:14px;margin-bottom:4px;">
                        ${p.NAMA ?? "Jaringan Irigasi"}
                    </div>

                    <table>
                        ${show("Nomenklatur", p.NOMENKLATU)}
                        ${show("Kode Saluran", p.K_SALURAN)}
                        ${show("Urut", p.URUT)}
                        ${show("Debit (Q)", p.Q)}
                        ${show("Luas (A)", p.A)}
                        ${show("Panjang", lengthMeter)}
                        ${show("Kondisi", p.KONDISI)}
                        ${show("Fungsi", p.FUNGSI)}
                        ${show("Tahun", p.TAHUN)}
                    </table>
                </div>
            `;

            layer.bindPopup(popupContent);

            layer.feature = {
                properties: {
                    name: `${p.NAMA} ${p.NOMENKLATU}` ?? "",
                    searchText: `
                ${p.NAMA ?? ""}
                ${p.NOMENKLATU ?? ""}
                ${p.K_BANGUNAN ?? ""}
            `,
                },
            };

            // 🔍 WAJIB: masukin ke searchLayer
            searchLayer.addLayer(layer);
        },
    }).addTo(mymap);
}

/* ===============================
   EVENT CHECKBOX
================================ */
document
    .getElementById("jaringan-cikunten-1")
    .addEventListener("change", function () {
        this.checked
            ? loadJaringanCikuntenI()
            : mymap.removeLayer(jaringanCikunten1Layer);
    });

document
    .getElementById("jaringan-cikunten-2")
    .addEventListener("change", function () {
        this.checked
            ? loadJaringanCikuntenII()
            : mymap.removeLayer(jaringanCikunten2Layer);
    });

// --- Control Search ---
// var searchControl = new L.Control.Search({
//     layer: searchLayer,
//     propertyName: "name",
//     marker: false,
//     moveToLocation: function (latlng, title, map) {
//         map.setView(latlng, 15);
//     },
//     position: "topright",
// });
// searchControl.on("search:locationfound", function (e) {
//     e.layer.openPopup();
// });
// mymap.addControl(searchControl);

// --- Efek highlight glowing pada sungai saat popup dibuka ---
let highlightedLayer = null;
let originalStyle = {}; // simpan warna & ketebalan aslinya

function highlightFeature(layer) {
    // Kalau ada sungai lain yang sedang menyala, reset dulu
    if (highlightedLayer) {
        highlightedLayer.setStyle(originalStyle);
    }

    // Simpan layer yang baru dipilih
    highlightedLayer = layer;

    // Simpan warna & ketebalan asli sebelum diubah
    originalStyle = {
        color: layer.options.color,
        weight: layer.options.weight,
        opacity: layer.options.opacity,
    };

    // Terapkan efek glowing
    layer.setStyle({
        weight: (layer.options.weight || 3) + 2,
        opacity: 1,
        className: "highlighted-sungai",
    });
}

// 💡 CSS efek glow lembut berdenyut
const style = document.createElement("style");
style.innerHTML = `
    .highlighted-sungai {
        filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.9))
                drop-shadow(0 0 12px rgba(255, 255, 255, 0.8))
                drop-shadow(0 0 18px rgba(255, 255, 255, 0.7));
        transition: all 0.3s ease;
        animation: sungai-glow-pulse 1.5s ease-in-out infinite;
    }

    @keyframes sungai-glow-pulse {
        0% {
            filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.9))
                    drop-shadow(0 0 12px rgba(255, 255, 255, 0.8))
                    drop-shadow(0 0 18px rgba(255, 255, 255, 0.7));
        }
        50% {
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 1))
                    drop-shadow(0 0 20px rgba(255, 255, 255, 0.9))
                    drop-shadow(0 0 30px rgba(255, 255, 255, 0.8));
        }
        100% {
            filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.9))
                    drop-shadow(0 0 12px rgba(255, 255, 255, 0.8))
                    drop-shadow(0 0 18px rgba(255, 255, 255, 0.7));
        }
    }
`;
document.head.appendChild(style);

// Saat popup dibuka → beri highlight
mymap.on("popupopen", function (e) {
    const layer = e.popup._source;
    if (sungaiLayer && sungaiLayer.hasLayer(layer)) {
        highlightFeature(layer);
    }
});

// Saat popup ditutup → balikan ke warna & style semula
mymap.on("popupclose", function () {
    if (highlightedLayer && originalStyle) {
        highlightedLayer.setStyle(originalStyle);
        highlightedLayer = null;
        originalStyle = {};
    }
});
