// --- Inisialisasi Peta ---
var mymap = L.map("map", {
    zoomControl: false,
}).setView([-7.2098686, 108.237827], 9);

// --- Layer dasar ---
const baseMaps = {
    // OpenStreetMap: L.tileLayer(
    //     "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
    //     { attribution: "&copy; OpenStreetMap contributors" }
    // ),
    // "Toner Lite": L.tileLayer(
    //     "https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png",
    //     { attribution: '&copy; <a href="https://carto.com/">CARTO</a>' }
    // ),
    // Satellite: L.tileLayer(
    //     "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
    //     { attribution: "&copy; Esri &mdash; Source: Esri, USGS, etc." }
    // ),
    // google: L.tileLayer("http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}", {
    //     maxZoom: 20,
    //     subdomains: ["mt0", "mt1", "mt2", "mt3"],
    // }),

    // Satelit Esri (tanpa label)
    "Esri Satellite": L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
        { attribution: "&copy; Esri &mdash; Source: Esri, USGS, etc." }
    ),

    // Google Streets
    "Google Streets": L.tileLayer(
        "http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}",
        {
            maxZoom: 20,
            subdomains: ["mt0", "mt1", "mt2", "mt3"],
        }
    ),

    // Google Hybrid (satelit + jalan + label)
    "Google Hybrid": L.tileLayer(
        "http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}",
        {
            maxZoom: 20,
            subdomains: ["mt0", "mt1", "mt2", "mt3"],
        }
    ),

    // Google Satellite (pure satelit)
    "Google Satellite": L.tileLayer(
        "http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",
        {
            maxZoom: 20,
            subdomains: ["mt0", "mt1", "mt2", "mt3"],
        }
    ),

    // Google Terrain (dengan kontur)
    "Google Terrain": L.tileLayer(
        "http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}",
        {
            maxZoom: 20,
            subdomains: ["mt0", "mt1", "mt2", "mt3"],
        }
    ),
};
baseMaps["Google Satellite"].addTo(mymap);

// kontrol layer & zoom
L.control
    .layers(baseMaps, null, { position: "topright", collapsed: false })
    .addTo(mymap);
L.control.zoom({ position: "bottomright" }).addTo(mymap);

// --- Marker icons ---
const iconMap = {
    embung: L.icon({
        iconUrl: "/img/embung.png",
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32],
    }),
    bendung: L.icon({
        iconUrl: "/img/bendung.png",
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32],
    }),
    bendungan: L.icon({
        iconUrl: "/img/bendungan.png",
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32],
    }),
    "pengaman pantai": L.icon({
        iconUrl: "/img/pengaman_pantai.png",
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32],
    }),
    "pengendali sedimen": L.icon({
        iconUrl: "/img/pengendali_sedimen.png",
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32],
    }),
    "pengendali banjir": L.icon({
        iconUrl: "/img/pengendali_banjir.png",
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32],
    }),
};

// --- URL API ---
const urlBM = "http://localhost:8000/api/data/bm";
const urlAsset = "http://localhost:8000/api/data/aset";

// --- Storage marker dipisah ---
var markersAset = {};
var markersBM = {};

// --- Layer khusus untuk pencarian ---
var searchLayer = L.layerGroup().addTo(mymap);

// --- Fungsi clear marker ---
function clearMarkers(storage, jenis) {
    if (storage[jenis]) {
        storage[jenis].forEach((m) => {
            mymap.removeLayer(m);
            searchLayer.removeLayer(m); // hapus juga dari searchLayer
        });
        storage[jenis] = [];
    }
}

// --- Fetch Data Aset ---
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
        const urlParams = new URL(urlAsset);
        urlParams.searchParams.append("jenis_aset[]", jenis);

        try {
            const res = await fetch(urlParams);
            const data = await res.json();

            clearMarkers(markersAset, jenis);
            markersAset[jenis] = [];

            data.forEach((item) => {
                const iconKey = (item.jenis_aset || "").toLowerCase();
                const marker = L.marker(
                    [parseFloat(item.lat), parseFloat(item.long)],
                    { icon: iconMap[iconKey] || new L.Icon.Default() }
                ).addTo(mymap);

                marker.bindPopup(`
                    <div class="card shadow-sm border-0" style="width:320px;font-size:0.85rem;border-radius:12px;overflow:hidden;">
                        <div class="card-header bg-primary text-white py-2 px-3">
                            <div class="fw-bold">${item.nama_aset}</div>
                            <small class="text-light">${item.jenis_aset}</small>
                        </div>
                        <div class="card-body p-3">
                            <div><b>Kode Integrasi:</b> ${item.no_registrasi}</div>
                            <div><b>Kode BMN:</b> ${item.kode_bmn}</div>
                            <div><b>Lokasi:</b> ${item.village}, ${item.district}, ${item.city}</div>
                        </div>
                        <div class="card-footer bg-light p-2">
                            <button class="btn btn-sm btn-primary w-100" onclick="detailAset(${item.id})">
                                <i class="fa fa-info-circle me-1"></i> Detail
                            </button>
                        </div>
                    </div>
                `);

                // simpan marker
                markersAset[jenis].push(marker);

                // masukkan marker ke searchLayer
                marker.feature = { properties: { name: item.nama_aset } };
                searchLayer.addLayer(marker);
            });
        } catch (err) {
            console.error("Error Aset:", err.message);
        }
    }
}

// --- Fetch Data Benchmark ---
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
        const urlParams = new URL(urlBM);
        urlParams.searchParams.append("jenis_pekerjaan[]", jenis);

        try {
            const res = await fetch(urlParams);
            const data = await res.json();

            clearMarkers(markersBM, jenis);
            markersBM[jenis] = [];

            data.forEach((item) => {
                const iconKey = (item.jenis_pekerjaan || "").toLowerCase();
                const marker = L.marker(
                    [parseFloat(item.lat), parseFloat(item.long)],
                    { icon: iconMap[iconKey] || new L.Icon.Default() }
                ).addTo(mymap);

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

                // masukkan marker ke searchLayer
                marker.feature = { properties: { name: item.nama_pekerjaan } };
                searchLayer.addLayer(marker);
            });
        } catch (err) {
            console.error("Error BM:", err.message);
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

// --- Control Search ---
var searchControl = new L.Control.Search({
    layer: searchLayer,
    propertyName: "name", // cari berdasarkan nama_aset / nama_pekerjaan
    marker: false,
    moveToLocation: function (latlng, title, map) {
        map.setView(latlng, 15);
    },
    position: "topright",
});

searchControl.on("search:locationfound", function (e) {
    e.layer.openPopup();
});

mymap.addControl(searchControl);

// fungsi untuk scale icon sesuai zoom
function getScaledIcon(baseIcon, zoom) {
    let scale = 1;
    if (zoom >= 15) scale = 1.8; // zoom besar → icon lebih besar
    else if (zoom >= 12) scale = 1.4;
    else if (zoom >= 10) scale = 1.2;

    return L.icon({
        iconUrl: baseIcon.options.iconUrl,
        iconSize: [32 * scale, 32 * scale],
        iconAnchor: [16 * scale, 32 * scale],
        popupAnchor: [0, -32 * scale],
    });
}
// setiap kali zoom berubah → resize semua marker
mymap.on("zoomend", () => {
    const zoom = mymap.getZoom();

    // resize marker aset
    Object.values(markersAset).forEach((markerArr) => {
        markerArr.forEach((m) => {
            const currentIcon = m.options.icon;
            m.setIcon(getScaledIcon(currentIcon, zoom));
        });
    });

    // resize marker benchmark
    Object.values(markersBM).forEach((markerArr) => {
        markerArr.forEach((m) => {
            const currentIcon = m.options.icon;
            m.setIcon(getScaledIcon(currentIcon, zoom));
        });
    });
});
