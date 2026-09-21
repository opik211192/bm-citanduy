<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data BM — {{ $bm->kode_bm }} | BBWS Citanduy</title>
    <link href="https://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 10px;
        }

        .card-bm {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-bm-header {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .card-bm-header img {
            max-width: 70px;
            margin-bottom: 8px;
        }

        .card-bm-header h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
        }

        .card-bm-header .kode-bm {
            font-size: 28px;
            font-weight: 700;
            margin-top: 10px;
            letter-spacing: 2px;
        }

        .card-bm-header .nama-pekerjaan {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 4px;
        }

        .section-title {
            background-color: #f0f0f0;
            padding: 8px 15px;
            font-size: 13px;
            font-weight: 700;
            color: #333;
            border-bottom: 2px solid #1a73e8;
        }

        .section-title i {
            margin-right: 6px;
            color: #1a73e8;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 10px 15px;
            font-size: 13px;
            border-bottom: 1px solid #eee;
        }

        .data-table td:first-child {
            font-weight: 600;
            color: #555;
            width: 40%;
        }

        .data-table td:last-child {
            color: #222;
        }

        .foto-gallery {
            padding: 15px;
        }

        .foto-gallery img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .foto-gallery img:hover {
            transform: scale(1.03);
        }

        .footer-info {
            background: #f8f9fa;
            padding: 12px 15px;
            text-align: center;
            font-size: 11px;
            color: #888;
            border-top: 1px solid #eee;
        }

        .badge-nfc {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .modal-backdrop.show {
            opacity: 0.85 !important;
            background-color: #000 !important;
        }

        .modal-body img {
            max-height: 85vh;
        }

        /* Mobile */
        @media (max-width: 576px) {
            body {
                padding: 5px;
            }

            .card-bm-header .kode-bm {
                font-size: 22px;
            }

            .data-table td {
                padding: 8px 12px;
                font-size: 12px;
            }

            .foto-gallery img {
                height: 120px;
            }
        }
    </style>
</head>

<body>

    <div class="card-bm">

        {{-- Header --}}
        <div class="card-bm-header">
            <img src="{{ asset('img/citanduy.png') }}" alt="Logo BBWS Citanduy">
            <h5>BALAI BESAR WILAYAH SUNGAI CITANDUY</h5>
            <div class="kode-bm">
                <i class="fas fa-map-marker-alt"></i> {{ $bm->kode_bm }}
            </div>
            @if($bm->nama_pekerjaan)
            <div class="nama-pekerjaan">{{ $bm->nama_pekerjaan }}</div>
            @endif
        </div>

        {{-- Lokasi --}}
        <div class="section-title"><i class="fas fa-map-marked-alt"></i> Lokasi</div>
        <table class="data-table">
            <tr>
                <td>Provinsi</td>
                <td>{{ $bm->provinsi ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kota / Kabupaten</td>
                <td>{{ $bm->kota ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kecamatan</td>
                <td>{{ $bm->kecamatan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Desa</td>
                <td>{{ $bm->desa ?? '-' }}</td>
            </tr>
        </table>

        {{-- Koordinat & Elevasi --}}
        <div class="section-title"><i class="fas fa-crosshairs"></i> Koordinat & Elevasi</div>
        <table class="data-table">
            <tr>
                <td>UTM X</td>
                <td>{{ $bm->utm_x ?? '-' }}</td>
            </tr>
            <tr>
                <td>UTM Y</td>
                <td>{{ $bm->utm_y ?? '-' }}</td>
            </tr>
            <tr>
                <td>Zone</td>
                <td>{{ $bm->zone ?? '-' }}</td>
            </tr>
            <tr>
                <td>Latitude</td>
                <td>{{ $bm->latitude ?? '-' }}</td>
            </tr>
            <tr>
                <td>Longitude</td>
                <td>{{ $bm->longitude ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tinggi Orthometrik</td>
                <td><strong>{{ $bm->tinggi_orthometrik ?? '-' }}</strong></td>
            </tr>
        </table>

        {{-- Info Tambahan --}}
        <div class="section-title"><i class="fas fa-info-circle"></i> Informasi Tambahan</div>
        <table class="data-table">
            <tr>
                <td>Keterangan</td>
                <td>{{ $bm->keterangan ?? '-' }}</td>
            </tr>
            <tr>
                <td>NFC ID</td>
                <td>
                    @if($bm->nfc_id)
                    <span class="badge-nfc"><i class="fas fa-wifi"></i> {{ $bm->nfc_id }}</span>
                    @else
                    <span style="color: #aaa;">-</span>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Galeri Foto --}}
        <div class="section-title"><i class="fas fa-images"></i> Galeri Foto</div>
        <div class="foto-gallery">
            @if($bm->photos->isNotEmpty())
            <div class="row g-2">
                @foreach($bm->photos as $photo)
                <div class="col-4">
                    <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Foto {{ $bm->kode_bm }}"
                        onclick="showImage('{{ asset('storage/' . $photo->file_path) }}')">
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center text-muted py-3">
                <i class="fas fa-camera fa-2x mb-2" style="opacity:0.3;"></i>
                <p style="font-size: 12px;">Belum ada foto</p>
            </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="footer-info">
            <i class="fas fa-qrcode"></i>
            Data Bench Mark — BBWS Citanduy<br>
            Diakses pada {{ now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
        </div>

    </div>

    {{-- Modal Preview Gambar --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                    <img id="modalImage" src="" class="img-fluid rounded shadow" alt="Preview">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showImage(src) {
            document.getElementById('modalImage').src = src;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    </script>
</body>

</html>