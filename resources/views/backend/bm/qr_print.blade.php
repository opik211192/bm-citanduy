<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code BM — BBWS Citanduy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .toolbar {
            max-width: 1000px;
            margin: 0 auto 20px;
            text-align: center;
        }

        .qr-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .qr-card {
            width: 200px;
            background: #fff;
            border: 2px solid #333;
            border-radius: 8px;
            text-align: center;
            padding: 12px;
            page-break-inside: avoid;
        }

        .qr-card .logo {
            width: 40px;
            margin-bottom: 5px;
        }

        .qr-card .instansi {
            font-size: 7px;
            font-weight: 700;
            color: #333;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .qr-card .qr-img {
            margin: 0 auto;
        }

        .qr-card .qr-img img,
        .qr-card .qr-img canvas {
            width: 140px !important;
            height: 140px !important;
        }

        .qr-card .kode {
            font-size: 14px;
            font-weight: 800;
            color: #0d47a1;
            margin-top: 6px;
            letter-spacing: 1px;
        }

        .qr-card .pekerjaan {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .qr-card .scan-text {
            font-size: 7px;
            color: #999;
            margin-top: 4px;
            border-top: 1px dashed #ccc;
            padding-top: 4px;
        }

        /* Print styles */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .toolbar {
                display: none !important;
            }

            .qr-grid {
                gap: 10px;
                justify-content: flex-start;
            }

            .qr-card {
                width: 180px;
                border-width: 1.5px;
                margin: 0;
                padding: 10px;
            }

            .qr-card .qr-img img,
            .qr-card .qr-img canvas {
                width: 120px !important;
                height: 120px !important;
            }
        }

        /* 4 columns when printing on A4 */
        @page {
            size: A4;
            margin: 10mm;
        }
    </style>
</head>

<body>

    {{-- Toolbar (hidden on print) --}}
    <div class="toolbar">
        <h4 class="mb-3"><i class="fas fa-qrcode"></i> QR Code Bench Mark</h4>
        <p class="text-muted">{{ $bms->count() }} QR Code — Siap dicetak dan ditempelkan pada titik BM</p>
        <button class="btn btn-primary btn-lg" onclick="window.print()">
            <i class="fas fa-print mr-2"></i> Cetak QR Code
        </button>
        <a href="{{ route('bm.index') }}" class="btn btn-secondary btn-lg ms-2">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    {{-- QR Cards --}}
    <div class="qr-grid">
        @foreach($bms as $bm)
        <div class="qr-card">
            <img src="{{ asset('img/citanduy.png') }}" class="logo" alt="Logo">
            <div class="instansi">BBWS CITANDUY</div>
            <div class="qr-img" id="qr-{{ $bm->id }}"></div>
            <div class="kode">{{ $bm->kode_bm }}</div>
            <div class="pekerjaan" title="{{ $bm->nama_pekerjaan }}">{{ $bm->nama_pekerjaan }}</div>
            <div class="scan-text"><i class="fas fa-mobile-alt"></i> Scan untuk info lengkap</div>
        </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const baseUrl = "{{ url('/bm/scan') }}";

            @foreach($bms as $bm)
            (function () {
                const qrCode = "{{ $bm->qr_code }}";
                const scanUrl = baseUrl + "/" + encodeURIComponent("{{ $bm->kode_bm }}");
                const container = document.getElementById("qr-{{ $bm->id }}");

                // Generate QR
                const qr = qrcode(0, 'M');
                qr.addData(scanUrl);
                qr.make();

                container.innerHTML = qr.createImgTag(4, 0);
            })();
            @endforeach
        });
    </script>

</body>

</html>