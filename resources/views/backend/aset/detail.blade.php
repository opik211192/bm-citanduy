<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Deskripsi Aset BBWS Citanduy</title>
    <link href="https://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        .header-table {
            margin-bottom: 20px;
        }

        .header-table img {
            max-width: 90px;
            max-height: 90px;
        }

        .header-table h5 {
            font-size: 15px;
            margin: 0;
        }

        .header-table div {
            font-size: 12px;
        }

        .header-table .col-right {
            text-align: center;
            font-size: 16px;
        }

        .section-title {
            background-color: #fcbc29;
            text-align: center;
            font-size: 16px;
            padding: 10px;
        }

        .modal-body img {
            max-height: 90vh;
        }

        img.previewable {
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        img.previewable:hover {
            transform: scale(1.03);
        }

        .modal-backdrop.show {
            opacity: 0.85 !important;
            background-color: #000 !important;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td colspan="4" style="text-align: center; vertical-align: middle">
                <img src="{{ asset('img/citanduy.png') }}" alt="Logo PUPR">
            </td>
            <td colspan="9" style="text-align: center">
                <h5><strong>BALAI BESAR WILAYAH SUNGAI CITANDUY</strong></h5>
                <div>JL. Prof. Dr. Ir. H. Sutami No. 1 Kota Banjar, Jawa Barat, 46300</div>
                <div>Telp (0265) 741686, Faks. (0265) 741302, email: bbws.citanduy@yahoo.com</div>
            </td>
            <td colspan="5" class="col-right">
                <div><b>Kode BMN</b></div>
                {{ $asset->kode_bmn }}
            </td>
        </tr>
    </table>

    <!-- Deskripsi Aset -->
    <table style="margin-top: 30px">
        <thead>
            <tr>
                <th colspan="18" class="section-title">DESKRIPSI ASET BBWS CITANDUY</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4"><b>No. Integrasi</b></td>
                <td colspan="14">{{ $asset->no_registrasi }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Nama Aset</b></td>
                <td colspan="14">{{ Str::title($asset->nama_aset) }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Jenis Aset</b></td>
                <td colspan="14">{{ Str::title($asset->jenis_aset) }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Desa/Kelurahan</b></td>
                <td colspan="5">{{ Str::title($asset->village->name) }}</td>
                <td colspan="4"><b>Kota/Kabupaten</b></td>
                <td colspan="5">{{ Str::title($asset->city->name) }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Kecamatan</b></td>
                <td colspan="5">{{ Str::title($asset->district->name) }}</td>
                <td colspan="4"><b>Provinsi</b></td>
                <td colspan="5">{{ Str::title($asset->province->name) }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Latitude</b></td>
                <td colspan="5">{{ number_format($asset->lat, 6) }}</td>
                <td colspan="4"><b>Longitude</b></td>
                <td colspan="5">{{ number_format($asset->long, 6) }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>X</b></td>
                <td colspan="5">{{ $asset->utm_x }}</td>
                <td colspan="4"><b>Y</b></td>
                <td colspan="5">{{ $asset->utm_y }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Tahun Mulai Pembangunan</b></td>
                <td colspan="5">{{ Str::title($asset->tahun_mulai_bangunan) }}</td>
                <td colspan="4"><b>Tahun Selesai Pembangunan</b></td>
                <td colspan="5">{{ Str::title($asset->tahun_selesai_bangunan) }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Kondisi Bangunan</b></td>
                <td colspan="14">{{ Str::title($asset->kondisi_bangunan) }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Keterangan</b></td>
                <td colspan="14">{{ Str::title($asset->keterangan) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Galeri Foto -->
    <table style="margin-top: 30px;">
        <thead>
            <tr>
                <th colspan="18" class="section-title">GALERI FOTO</th>
            </tr>
        </thead>
        <tbody>
            @php $count = 0; @endphp
            @if($asset->photos->isNotEmpty())
            <tr>
                @foreach($asset->photos as $photo)
                <td colspan="6" style="text-align: center; padding: 10px;">
                    <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Foto Aset" class="previewable"
                        onclick="showImageInModal('{{ asset('storage/' . $photo->file_path) }}')"
                        style="max-width: 200px; height: 150px; object-fit: cover;">
                </td>
                @php
                $count++;
                if($count % 3 == 0) echo '
            </tr>
            <tr>';
                @endphp
                @endforeach
            </tr>
            @else
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #888;">
                    Tidak ada foto tersedia
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <table style="margin-top: 50px; border: none; width: 100%;">

    </table>

    <!-- Modal Gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                    <img id="modalImage" src="" class="img-fluid rounded shadow" alt="Preview Gambar">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showImageInModal(src) {
            const modalImg = document.getElementById("modalImage");
            modalImg.src = src;
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }
    </script>
</body>

</html>