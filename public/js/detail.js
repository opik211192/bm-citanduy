async function detailbm(id) {
    try {
        const detailResponse = await fetch(
            `http://localhost:8000/api/data/bm/${id}`
        );
        if (!detailResponse.ok) {
            throw new Error("Network response was not ok");
        }
        const detailData = await detailResponse.json();
        const detailContent = `
        <iframe src="/benchmark/print/${detailData.id}" title="Benchmark Detail" width="100%" height="600" ></iframe>
        <button id="download-button" class="btn btn-primary btn-sm mb-2">Download</button>
        `;
        document.getElementById("detail-content").innerHTML = detailContent;
        document.getElementById("sidebar-right").classList.add("active");

        document
            .getElementById("download-button")
            .addEventListener("click", () => {
                window.open(`/benchmark/download/${detailData.id}`, "_blank");
            });
    } catch (error) {
        console.error("Failed to fetch detail data:", error);
    }
}

async function detailAset(id) {
    const detailContentDiv = document.getElementById("detail-content");
    const downloadBtn = document.getElementById("download-aset-button");

    // tampilkan loading
    detailContentDiv.innerHTML = `
        <div class="d-flex flex-column justify-content-center align-items-center" 
             style="height:300px;">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Loading detail...</p>
        </div>
    `;
    downloadBtn.classList.add("d-none"); // sembunyikan dulu

    try {
        const detailResponse = await fetch(`/api/data/aset/${id}`);
        if (!detailResponse.ok) throw new Error("Network response was not ok");

        const detailAset = await detailResponse.json();

        // tampilkan iframe
        detailContentDiv.innerHTML = `
            <iframe src="/aset/print/${detailAset.id}" 
                title="Aset Detail" width="100%" height="600"></iframe>
        `;

        // aktifkan tombol download di header
        downloadBtn.classList.remove("d-none");
        downloadBtn.onclick = () => {
            //window.open(`/aset/download/${detailAset.id}`, "_blank");
            //window.open("#");
        };

        document.getElementById("sidebar-right").classList.add("active");
    } catch (error) {
        detailContentDiv.innerHTML = `<p class="text-danger">Gagal memuat detail</p>`;
        console.error("Failed to fetch detail aset:", error);
    }
}

async function detailAirbaku(id) {
    const detailContentDiv = document.getElementById("detail-content");
    const downloadBtn = document.getElementById("download-aset-button");

    // tampilkan loading dulu
    detailContentDiv.innerHTML = `
        <div class="d-flex flex-column justify-content-center align-items-center" 
             style="height:300px;">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Loading detail air baku...</p>
        </div>
    `;
    downloadBtn.classList.add("d-none"); // sembunyikan tombol download sementara

    try {
        const detailResponse = await fetch(`/api/data/airbaku/${id}`);
        if (!detailResponse.ok) throw new Error("Network response was not ok");

        const detailAirbaku = await detailResponse.json();

        // tampilkan iframe detail
        detailContentDiv.innerHTML = `
            <iframe src="/airbaku/print/${detailAirbaku.id}" 
                title="Air Baku Detail" width="100%" height="600"></iframe>
        `;

        // aktifkan tombol download
        downloadBtn.classList.remove("d-none");
        downloadBtn.onclick = () => {
            //window.open(`/airbaku/download/${detailAirbaku.id}`, "_blank");
            //window.open("#");
        };

        document.getElementById("sidebar-right").classList.add("active");
    } catch (error) {
        detailContentDiv.innerHTML = `<p class="text-danger">Gagal memuat detail air baku</p>`;
        console.error("Failed to fetch detail airbaku:", error);
    }
}
