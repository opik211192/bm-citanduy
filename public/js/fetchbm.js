// Ambil elemen checkbox berdasarkan ID
const url = "http://localhost:8000/api/data/bm";
var markers = []; // Array untuk menyimpan semua marker

async function fetchData() {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        const data = await response.json();

        // Hapus semua marker sebelum menambahkan yang baru
        markers.forEach((marker) => mymap.removeLayer(marker));
        markers = []; // Kosongkan array marker

        // Loop melalui semua data dan tambahkan marker untuk setiap data
        data.forEach((item) => {
            const marker = L.marker([
                parseFloat(item.lat),
                parseFloat(item.long),
            ]).addTo(mymap);
            marker.bindPopup(
                `<table class="table table-sm table-bordered">
                <tr>
                    <th>Kode BM</th>
                    <td>${item.kode_bm}</td>
                </tr>
                <tr>
                    <th>Nama Pekerjaan</th>
                    <td>${item.nama_pekerjaan}</td>
                </tr>
            </table>
            <btn class="btn btn-primary btn-sm" onclick="detailbm(${item.id})" id="detailbm">Detail</btn>`
            );
            markers.push(marker); // Tambahkan marker ke array
        });
    } catch (error) {
        console.error("Error fetching data:", error.message);
    }
}

// Ambil elemen checkbox berdasarkan ID
var checkboxBM = document.getElementById("checkedbm");

// Tambahkan event listener untuk mendengarkan perubahan status checkbox
checkboxBM.addEventListener("change", function () {
    // Jika checkbox dicentang
    if (this.checked) {
        // Tambahkan spinner.style.display = "block"; sebelum memanggil fetchData()
        spinner.style.display = "block";

        // Panggil fetchData() setelah sedikit jeda untuk menunjukkan spinner
        setTimeout(() => {
            fetchData().finally(() => {
                spinner.style.display = "none"; // Sembunyikan spinner setelah proses selesai
            });
        }, 500);
    } else {
        // Jika checkbox tidak dicentang dan marker sudah ada, hapus dari peta
        if (markers.length > 0) {
            markers.forEach((marker) => mymap.removeLayer(marker));
            markers = []; // Kosongkan array marker
        }
    }
});

function detailbm(id) {
    console.log(id);
    var detailbm = document.getElementById("detailbm");
    detailbm.addEventListener("click", function () {
        document.getElementById("sidebar-right").classList.toggle("active");
        this.classList.toggle("active");
    });
}
