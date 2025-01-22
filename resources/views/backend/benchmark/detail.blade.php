<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deskripsi Benchmark Citanduy</title>
    <link href="https://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">
    {{--
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.rtl.min.css"
        integrity="sha512-rXhL8a+X3wTosWn2Zgd/5L8rfWrpff7qdOnl7Wg1n2zHk8lHGhiSujoyxoKw1nf43kZAzJoe0Z0ymr2Kkku7lQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        /* body {
            font-family: 'Poppins', sans-serif;
        } */
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
            /* background-color: #aad3df; */
            text-align: center;
            font-size: 16px;
            padding: 10px;
        }

        .no-padding {
            padding: 0;
        }
    </style>
</head>

<body>

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
                <div><b>Kode BM</b> </div>
                {{ $benchmark->kode_bm }}
            </td>
        </tr>
    </table>

    <table style="margin-top: 30px">
        <thead>
            <tr>
                <th colspan="18" class="section-title">DESKRIPSI BENCH MARK (BM)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4"><b>No. Registrasi</b></td>
                <td colspan="14">{{ $benchmark->no_registrasi }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Nama Pekerjaan</b></td>
                <td colspan="14">{{ $benchmark->nama_pekerjaan }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Desa/Kelurahan</b></td>
                <td colspan="5">{{ $benchmark->village->name }}</td>
                <td colspan="4"><b>Kota/Kabupaten</b></td>
                <td colspan="5">{{ $benchmark->city->name }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Kecamatan</b></td>
                <td colspan="5">{{ $benchmark->district->name }}</td>
                <td colspan="4"><b>Provinsi</b></td>
                <td colspan="5">JAWA TENGAH</td>
            </tr>
            <tr>
                <td colspan="4"><b>Latitude</b></td>
                <td colspan="5">{{ number_format($benchmark->lat, 6) }}</td>
                <td colspan="4"><b>Longitude</b></td>
                <td colspan="5">{{ number_format($benchmark->long, 6) }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>X</b></td>
                <td colspan="5">{{ $benchmark->utm_x }}</td>
                <td colspan="4"><b>Y</b></td>
                <td colspan="5">{{$benchmark->utm_y }}</td>
            </tr>
            <tr>
                <td colspan="4"><b>Uraian Lokasi</b></td>
                <td colspan="14">
                    {{ $benchmark->keterangan }}
                </td>
            </tr>
            {{-- <tr>
                <td colspan="4">Alamat</td>
                <td colspan="14">PAKU LAUT</td>
            </tr>
            <tr>
                <td colspan="4">Bentuk Fisik</td>
                <td colspan="14">-</td>
            </tr> --}}
        </tbody>
    </table>

    <table style="margin-top: 30px;">
        <thead>
            <tr>
                <th colspan="18" class="section-title">FOTO DAN SKETSA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="9" style="text-align:center">FOTO</th>
                <th colspan="9" style="text-align:center">SKETSA</th>
            </tr>
            <tr>
                <td colspan="9" style="text-align:center">
                    @if ($benchmark->uploads->foto)
                    <img src="{{ asset('storage/img/foto/' . $benchmark->uploads->foto) }}" alt="Foto"
                        style="max-width: 100%; height: auto;">
                    @endif
                </td>
                <td colspan="9" style="text-align:center">
                    @if ($benchmark->uploads->sketsa)
                    <img src="{{ asset('storage/img/sketsa/' . $benchmark->uploads->sketsa) }}" alt="Sketsa"
                        style="max-width: 100%; height: auto;">
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    {{-- <table>
        <thead>
            <tr>
                <th colspan="18" class="section-title">KOORDINAT PROYEKSI & KONDISI PILAR</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" style="text-align: center;">Zona</td>
                <td colspan="14" style="text-align: center;">49S</td>
            </tr>
            <tr>
                <td colspan="4">Utara</td>
                <td colspan="5">9214764,453 meter</td>
                <td colspan="4">Timur</td>
                <td colspan="5">279920,206 meter</td>
            </tr>
            <tr>
                <td colspan="4">Faktor Skala</td>
                <td colspan="5">1.0002</td>
                <td colspan="4">Kondisi Acuan Vertikal</td>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td colspan="4">Konversi Grid</td>
                <td colspan="5">14' 46.46''</td>
                <td colspan="4">Tinggi Orthometrik (H)</td>
                <td colspan="5">47.867 meter</td>
            </tr>
            <tr>
                <td colspan="4">Kondisi Pilar</td>
                <td colspan="14"></td>
            </tr>
        </tbody>
    </table> --}}

</body>

</html>