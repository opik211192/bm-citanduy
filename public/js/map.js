// Inisialisasi peta dengan mematikan kontrol zoom bawaan
var mymap = L.map("map", {
    zoomControl: false,
}).setView([-7.2098686, 108.237827], 9);

// Definisikan beberapa layer peta dasar
const baseMaps = {
    OpenStreetMap: L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            attribution: "&copy; OpenStreetMap contributors",
        }
    ),
    "Toner Lite": L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png",
        {
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
        }
    ),
    Satellite: L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
        {
            attribution:
                "&copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye",
        }
    ),
};

// Tambahkan salah satu sebagai default
baseMaps["Satellite"].addTo(mymap);

// Tambahkan kontrol layer di kanan atas
L.control
    .layers(baseMaps, null, {
        position: "topright",
        collapsed: false,
    })
    .addTo(mymap);

// Tambahkan kontrol zoom manual di kanan bawah
L.control
    .zoom({
        position: "bottomright",
    })
    .addTo(mymap);

// URL API data marker
const url = "http://localhost:8000/api/data/bm";
var markers = []; // Array untuk menyimpan semua marker

// Fungsi ambil data dari API dan tampilkan marker
async function fetchData() {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        const data = await response.json();

        // Bersihkan marker lama
        markers.forEach((marker) => mymap.removeLayer(marker));
        markers = [];

        // Tambahkan marker baru dari data API
        data.forEach((item) => {
            console.log(item);

            const marker = L.marker([
                parseFloat(item.lat),
                parseFloat(item.long),
            ]).addTo(mymap);

            marker.bindPopup(`
                <div class="border rounded bg-white shadow-sm p-3" style="width: 300px; font-size: 0.8rem; line-height: 1.4;">
                    <div class="mb-2">
                        <div class="fw-semibold " style="font-size: 0.9rem;">
                            ${item.nama_pekerjaan}
                        </div>
                        <small class="text-muted">Informasi Pekerjaan</small>
                    </div>
                    <hr class="my-2"/>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Kode BM</span>
                            <span class="text-dark">${item.kode_bm}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">No. Registrasi</span>
                            <span class="text-dark">${item.no_registrasi}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Provinsi</span>
                            <span class="text-dark">${item.province}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Kota</span>
                            <span class="text-dark">${item.city}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Kecamatan</span>
                            <span class="text-dark">${item.district}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Desa</span>
                            <span class="text-dark">${item.village}</span>
                        </div>
                    </div>

                    <button class="btn btn-sm btn-outline-primary w-100 mt-2" onclick="detailbm(${item.id})">
                        Detail
                    </button>
                </div>
            `);

            markers.push(marker);
        });
    } catch (error) {
        console.error("Error fetching data:", error.message);
    }
}

// Ambil elemen checkbox berdasarkan ID
var checkboxBM = document.getElementById("checkedbm");

// Tambahkan event listener untuk mengontrol tampil / sembunyi marker
checkboxBM.addEventListener("change", function () {
    if (this.checked) {
        spinner.style.display = "block";
        setTimeout(() => {
            fetchData().finally(() => {
                spinner.style.display = "none";
            });
        }, 500);
    } else {
        if (markers.length > 0) {
            markers.forEach((marker) => mymap.removeLayer(marker));
            markers = [];
        }
    }
});
