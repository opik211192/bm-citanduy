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
