<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Informasi Geospasial Citanduy</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/citanduy.png') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-search@3.0.0/dist/leaflet-search.min.css" />

    <style>
        /* Style sama seperti punyamu, tidak diubah */
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Nunito', sans-serif;
        }

        #map {
            position: absolute;
            /* top: 56px; */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10000;
        }

        .navbar-toggler {
            background-color: #007bff;
            border: none;
            color: #fff;
            font-size: 24px;
            display: none;
        }

        .toggle-btn,
        .toggle-btn-right {
            display: none;
        }

        .navbar-collapse {
            display: flex;
            justify-content: flex-end;
        }

        .sidebar {
            position: fixed;
            top: 70px;
            bottom: 0;
            width: 375px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            border-radius: 0 10px 10px 0;
            overflow-y: auto;
            z-index: 9999;
            transition: left 0.3s ease;
            left: -375px;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar.right {
            right: -720px;
            width: 720px;
            left: auto;
            overflow: hidden;
        }

        .sidebar.right.active {
            right: 0;
        }

        .sidebar-header .btn-close {
            cursor: pointer;
        }

        .mini-menu {
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            background: rgba(0, 123, 255, 0.4);
            /* transparan */
            backdrop-filter: blur(4px);
            /* efek blur kaca */
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 10000;
            padding: 5px;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* biar agak timbul */
            transition: background 0.3s ease;
        }

        .mini-menu:hover {
            background: rgba(0, 123, 255, 0.7);
            /* lebih pekat pas hover */
        }

        .mini-menu div {
            padding: 10px 5px;
            font-size: 18px;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .form-check-input {
            width: 24px;
            height: 24px;
            cursor: pointer;
            border: 1px solid black;
        }

        .form-check-label {
            margin-left: 10px;
            font-size: 16px;
        }

        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
            border: 2px solid #007bff;
        }

        .sidebar-header {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .sidebar-header h3 {
            margin: 0;
        }

        #spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }

        @media (max-width: 768px) {

            .toggle-btn,
            .toggle-btn-right {
                display: block;
                position: absolute;
                top: 15px;
            }

            .toggle-btn {
                left: 10px;
            }

            .toggle-btn-right {
                right: 10px;
            }

            .navbar-collapse {
                display: none !important;
            }

            .navbar-toggler {
                display: inline;
            }

            .sidebar {
                width: 100%;
                left: -100%;
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar.right {
                right: -100%;
            }

            .sidebar.right.active {
                right: 0;
            }

            .sidebar.left .btn-close {
                display: none;
            }

            .sidebar.left.active .btn-close {
                display: block;
            }
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .leaflet-popup-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 320px !important;
        }

        .leaflet-popup-tip {
            left: 50% !important;
            /* bikin panah popup center ke marker */
            transform: translateX(-50%) !important;
        }

        /* Ubah warna tombol close bawaan Leaflet jadi putih */
        .leaflet-popup-close-button {
            color: #fff !important;
            /* warna icon X */
            font-weight: bold;
            opacity: 0.9;
        }

        .leaflet-popup-close-button:hover {
            color: #f8d7da !important;
            /* warna saat hover, misalnya pink muda */
            opacity: 1;
        }

        /* Balikin posisi default (stack vertikal kanan atas) */
        .leaflet-top.leaflet-right {
            display: block;
            margin-top: 80px;
            /* tetap bisa geser turun biar ga ketabrak navbar */
        }

        /* Reset margin biar rapih */
        .leaflet-control-search,
        .leaflet-control-layers {
            margin: 0 0 5px 0 !important;
            /* kasih jarak bawah antar control */
        }

        /* Search control default: ikon mentok kanan */
        .leaflet-control-search {
            float: right !important;
        }

        /* Input search posisinya expand ke kiri */
        .leaflet-control-search .search-input {
            text-align: left;
            padding-right: 30px;
            width: 260px;
            transition: width 0.3s ease;
        }

        .leaflet-control-search.active .search-input {
            width: 260px;
        }

        .leaflet-control-search .search-input:focus {
            width: 260px;
            /* ukuran pas diklik/focus */
        }

        /* Posisikan tombol search (ikon lupanya) di kanan */
        .leaflet-control-search .search-button {
            right: 0 !important;
            left: auto !important;
            border-radius: 0 4px 4px 0;
            /* sudut kanan membulat */
        }

        #coordinate-box {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 8px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            z-index: 9999;
            display: none;
            /* default hidden */
        }

        #reset-coord {
            background: none;
            border: none;
            color: red;
            font-size: 16px;
            font-weight: bold;
            margin-left: 10px;
            cursor: pointer;
        }

        #reset-coord:hover {
            color: darkred;
        }

        #toggle-coord {
            position: absolute;
            bottom: 50px;
            /* di atas coordinate box */
            left: 50%;
            transform: translateX(-50%);
            background: #007bff;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            z-index: 10000;
            transition: background 0.2s;
        }

        #toggle-coord:hover {
            background: #0056b3;
        }

        #coordinate-box {
            display: none;
            /* default tersembunyi */
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 50%;
                /* jangan 100%, misalnya 80% saja */
                left: -80%;
                /* posisi awal sembunyi */
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar.right {
                width: 80%;
                /* kanan juga jangan full */
                right: -80%;
            }

            .sidebar.right.active {
                right: 0;
            }
        }
    </style>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body>
    <!-- Navbar -->
    <div class="navbar navbar-expand-lg fixed-top shadow-sm"
        style="background: rgba(0, 123, 255, 0.4); backdrop-filter: blur(5px);" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <div class="d-flex">
                    <img src="{{ asset('img/citanduy.png') }}" alt="logo citanduy" width="70" height="45" class="me-3"
                        style="filter: drop-shadow(0 0 8px rgba(0,123,255,0.9));">
                    <span class="align-self-center text-wrap" style="max-width: 250px; line-height: 1;">
                        SISTEM INFORMASI <br> GEOSPASIAL CITANDUY
                    </span>
                </div>
            </a>

            <!-- Tombol toggle sidebar kiri -->
            <div class="toggle-btn d-lg-none" id="toggle-btn">&#9776;</div>

            <!-- Menu kanan (desktop) -->
            <div class="collapse navbar-collapse d-none d-lg-block" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item">
                        @if (Route::has('login'))
                        @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                        @else
                        <a href="{{ route('login') }}" class="nav-link">Log in</a>
                        @endauth
                        @endif
                    </li>
                </ul>
            </div>

            <!-- Menu kanan (mobile) -->
            <div class="d-lg-none">
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-sm btn-primary">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Log in</a>
                @endauth
                @endif
            </div>
        </div>
    </div>

    <!-- Mini Menu -->
    <div class="mini-menu" onclick="toggleSidebar()">
        <div>&#9776;</div>
    </div>

    <!-- Left Sidebar -->
    <div class="sidebar left" id="sidebar">
        <div class="sidebar-header border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-uppercase" style="font-size: 1rem; letter-spacing: .5px;">
                Data Peta
            </span>
            <button type="button" class="btn-close ms-2" aria-label="Close" onclick="toggleSidebar()"
                style="font-size: .9rem;"></button>
        </div>

        <div class="accordion accordion-flush px-2 pt-1 mt-2" id="sidebarAccordion">

            <!-- Tampilkan DAS -->
            <div class="accordion-item border rounded shadow-sm mb-3">
                <h2 class="accordion-header" id="headingBatas">
                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseBatas" aria-expanded="true" aria-controls="collapseBatas">
                        <i class="fa-solid fa-water  me-2 text-danger"></i>DAS
                    </button>
                </h2>
                <div id="collapseBatas" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="batas-das">
                            <label class="form-check-label fw-bold" for="batas-das">
                                <i class="fa-solid fa-draw-polygon me-2 text-primary"></i>Batas DAS
                            </label>
                        </div>
                        <!-- Dropdown Sungai -->
                        <div class="mb-2">
                            <div class="d-flex align-items-center justify-content-between sungai-header"
                                data-bs-toggle="collapse" data-bs-target="#collapseSungai" aria-expanded="false"
                                aria-controls="collapseSungai" style="cursor: pointer;">

                                <div class="d-flex align-items-center">
                                    <input class="form-check-input sungai-filter" type="checkbox" id="sungai-all"
                                        value="all">
                                    <label class="form-check-label fw-bold d-flex align-items-center mb-0"
                                        for="sungai-all">
                                        <i class="bi bi-water me-2 text-primary"></i>
                                        Sungai
                                    </label>
                                </div>

                                <i class="bi bi-caret-down-fill toggle-icon"></i>
                            </div>

                            <div class="collapse" id="collapseSungai">
                                <div class="pt-3 px-3" style="max-height: 220px; overflow-y: auto;">
                                    <div class="form-check mb-2 border-bottom pb-2">
                                        <input class="form-check-input sungai-filter" type="checkbox"
                                            id="sungai-all-inner" value="all">
                                        <label class="form-check-label" for="sungai-all-inner">Semua (All)</label>
                                    </div>

                                    @foreach ($ordes as $orde)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input sungai-filter" type="checkbox"
                                            id="sungai-{{ $orde }}" value="{{ $orde }}">
                                        <label class="form-check-label" for="sungai-{{ $orde }}">
                                            Orde {{ $orde }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aset Section -->
            <div class="accordion-item border rounded shadow-sm mb-3">
                <h2 class="accordion-header" id="headingAset">
                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseAset" aria-expanded="true" aria-controls="collapseAset">
                        <i class="fa-solid fa-building me-2 text-primary"></i>Data Infrastruktur
                    </button>
                </h2>
                <div id="collapseAset" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        @foreach ($jenisPekerjaanAset as $jenis)
                        @php
                        // fungsi sama dengan getColorByJenis di JS
                        $color = match(strtolower($jenis)) {
                        'embung' => '#0d6efd', // biru
                        'bendung' => '#6c757d', // abu
                        'bendungan' => '#0dcaf0', // cyan
                        'pengaman pantai' => '#6f42c1', // purple
                        'pengendali sedimen' => '#9c956d', // sage
                        'pengendali banjir' => '#d63384', // pink
                        default => '#212529', // dark
                        };
                        @endphp
                        <div class="form-check mb-2">
                            <input class="form-check-input jenis-aset" type="checkbox" id="aset-{{ $jenis }}"
                                value="{{ $jenis }}">
                            <label class="form-check-label d-flex align-items-center gap-2 fw-bold"
                                for="aset-{{ $jenis }}">
                                <div style="
                                    background: {{ $color }};
                                    width: 24px;
                                    height: 24px;
                                    border-radius: 50%;
                                    border: 2px solid white;
                                    box-shadow: 0 0 3px rgba(0,0,0,0.5);
                                "></div>
                                <span class="fw-bold">{{ ucfirst($jenis) }}</span>
                                <span class="fw-normal">({{ $asetCounts[$jenis] ?? 0 }})</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>



            {{--
            <!-- Benchmark Section -->
            <div class="accordion-item border rounded shadow-sm mb-3">
                <h2 class="accordion-header" id="headingBenchmark">
                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseBenchmark" aria-expanded="true" aria-controls="collapseBenchmark">
                        <i class="fa-solid fa-map me-2 text-success"></i>Data Benchmark
                    </button>
                </h2>
                <div id="collapseBenchmark" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        @foreach ($jenisPekerjaanListBenchmark as $jenis)
                        <div class="form-check mb-2">
                            <input class="form-check-input jenis-benchmark" type="checkbox" id="bm-{{ $jenis }}"
                                value="{{ $jenis }}">
                            <label class="form-check-label d-flex align-items-center gap-2" for="bm-{{ $jenis }}">
                                <img src="{{ asset('img/' . strtolower($jenis) . '.png') }}" width="24" height="24">
                                {{ ucfirst($jenis) }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div> --}}

            {{-- <div class="accordion-item border rounded shadow-sm mb-3">
                <h2 class="accordion-header" id="headingAirBaku">
                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseAirBaku" aria-expanded="true" aria-controls="collapseAirBaku">
                        <i class="fa-solid fa-droplet me-2 text-success"></i>Air Baku (SIATAB)
                    </button>
                </h2>
                <div id="collapseAirBaku" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        @foreach ($jenisAirBaku as $jenis)
                        <div class="form-check mb-2">
                            <input class="form-check-input jenis-air-baku" type="checkbox" id="airbaku-{{ $jenis }}"
                                value="{{ $jenis }}">
                            <label class="form-check-label d-flex align-items-center gap-2 fw-bold"
                                for="airbaku-{{ $jenis }}">
                                @switch($jenis)
                                @case('Sumur')
                                <i class="fa-solid fa-water text-primary"></i>
                                @break
                                @case('Mata Air')
                                <i class="fa-solid fa-droplet text-success"></i>
                                @break
                                @case('Intake Sungai')
                                <i class="fa-solid fa-arrow-down-wide-short text-info"></i>
                                @break
                                @case('PAH/ABSAH')
                                <i class="fa-solid fa-jar text-warning"></i>
                                @break
                                @case('Tampungan Air Baku')
                                <i class="fa-solid fa-database text-danger"></i>
                                @break
                                @default
                                <i class="fa-solid fa-circle text-secondary"></i>
                                @endswitch
                                {{ $jenis }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div> --}}
            <div class="accordion-item border rounded shadow-sm mb-3">
                <h2 class="accordion-header" id="headingAirBaku">
                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseAirBaku" aria-expanded="true" aria-controls="collapseAirBaku">
                        <i class="fa-solid fa-droplet me-2 text-success"></i>Air Baku (SIATAB)
                    </button>
                </h2>
                <div id="collapseAirBaku" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        @foreach ($jenisAirBaku as $jenis)
                        @php
                        // warna berbeda dengan aset
                        $color = match($jenis) {
                        'Sumur' => '#198754', // hijau
                        'Mata Air' => '#20c997', // teal
                        'Intake Sungai' => '#0dcaf0', // cyan
                        'PAH/ABSAH' => '#ffc107', // kuning
                        'Tampungan Air Baku' => '#9f4951', // merah
                        default => '#6c757d', // abu
                        };
                        @endphp
                        <div class="form-check mb-2">
                            <input class="form-check-input jenis-air-baku" type="checkbox" id="airbaku-{{ $jenis }}"
                                value="{{ $jenis }}">
                            <label class="form-check-label d-flex align-items-center gap-2 fw-bold"
                                for="airbaku-{{ $jenis }}">
                                <div style="
                                        background: {{ $color }};
                                        width: 24px;
                                        height: 24px;
                                        border-radius: 50%;
                                        border: 2px solid white;
                                        box-shadow: 0 0 3px rgba(0,0,0,0.5);
                                    "></div>
                                <span class="fw-bold">{{ ucfirst($jenis) }}</span>
                                <span class="fw-normal">({{ $airBakuCounts[$jenis] ?? 0 }})</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div style="z-index: 10000;" class="sidebar right" id="sidebar-right">
        <div class="sidebar-header d-flex justify-content-between align-items-center border-bottom px-2 py-1">
            <button type="button" id="download-aset-button" class="btn btn-sm btn-outline-secondary d-none"
                title="Cetak / Download">
                <i class="fas fa-print"></i>
            </button>
            <button type="button" class="btn-close" id="close-btn-right" aria-label="Close"
                onclick="toggleDetailSidebar()"></button>
        </div>
        <div id="detail-content" class="p-2"></div>
    </div>

    <!-- Map -->
    <div id="map">
        <div id="spinner" style="display: none;">
            <div class="spinner-grow" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Tombol toggle koordinat -->
    {{-- <div id="toggle-coord" onclick="toggleCoordinateBox()">
        <i class="fa-solid fa-location-crosshairs"></i>
    </div> --}}

    <!-- Koordinat Info -->
    {{-- <div id="coordinate-box">
        <span id="coord-text">Klik peta untuk melihat koordinat</span>
        <button id="reset-coord" onclick="resetCoordinateBox()">×</button>
    </div> --}}


    <!-- JS -->
    <script src="https://unpkg.com/leaflet-search@3.0.0/dist/leaflet-search.min.js"></script>

    <script>
        const APP_URL = "{{ url('') }}";
            const urlBM = APP_URL + "/api/data/bm";
            const urlAsset = APP_URL + "/api/data/aset";
            const urlAirbaku = APP_URL + "/api/data/airbaku";
    </script>
    <script src="{{ asset('js/map.js') }}"></script>
    <script src="{{ asset('js/detail.js') }}"></script>
    <script>
        function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const miniMenu = document.querySelector('.mini-menu');
                sidebar.classList.toggle('active');
                miniMenu.style.display = sidebar.classList.contains('active') ? 'none' : 'flex';
            }
            function toggleDetailSidebar() {
                document.getElementById('sidebar-right').classList.toggle('active');
            }
            document.getElementById('toggle-btn').addEventListener('click', toggleSidebar);
    </script>
    <script>
        let coordMarker = null; // simpan marker terakhir
        
            function toggleCoordinateBox() {
                const box = document.getElementById("coordinate-box");
                box.style.display = (box.style.display === "none" || box.style.display === "") ? "block" : "none";
                    
            }
        
            function resetCoordinateBox() {
                document.getElementById("coord-text").innerHTML = "Klik peta untuk melihat koordinat";
                document.getElementById("coordinate-box").style.display = "none";
                if (coordMarker) {
                    mymap.removeLayer(coordMarker); // hapus marker
                    coordMarker = null;
                }
            }
        
            mymap.on("click", function (e) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);
        
                // Update teks di box
                document.getElementById("coord-text").innerHTML = `Lat: <b>${lat}</b>, Lng: <b>${lng}</b>`;
                document.getElementById("coordinate-box").style.display = "block";
        
                // Hapus marker lama kalau ada
                if (coordMarker) {
                    mymap.removeLayer(coordMarker);
                }
        
                // Tambahkan marker baru
                coordMarker = L.marker([lat, lng]).addTo(mymap);
            });
    </script>
    <script>
        let batasDasLayer = null;
        
            // Load GeoJSON sekali saja
            fetch("{{ asset('js/batasDas.geojson') }}")
                .then(res => res.json())
                .then(data => {
                    batasDasLayer = L.geoJSON(data, {
                        style: {
                            color: "blue",
                            weight: 2,
                            fillOpacity: 0.05
                        },
                        onEachFeature: function (feature, layer) {
                            if (feature.properties && feature.properties.nama) {
                                layer.bindPopup("DAS: " + feature.properties.nama);
                            }
                        }
                    });
                });
            
            // Toggle pakai checkbox
            document.getElementById("batas-das").addEventListener("change", function(e) {
                if (e.target.checked) {
                    batasDasLayer.addTo(mymap);
                    mymap.fitBounds(batasDasLayer.getBounds());
                } else {
                    mymap.removeLayer(batasDasLayer);
                }
            });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const sungaiAllOuter = document.getElementById('sungai-all');
        const sungaiAllInner = document.getElementById('sungai-all-inner');
        const sungaiItems = document.querySelectorAll('.sungai-filter:not(#sungai-all):not(#sungai-all-inner)');
    
        function syncAll(checked) {
            sungaiItems.forEach(cb => cb.checked = checked);
            sungaiAllOuter.checked = checked;
            sungaiAllInner.checked = checked;
            updateSungaiLayer(checked ? ["all"] : []); // fungsi kamu untuk refresh layer
        }
    
        sungaiAllOuter.addEventListener('change', e => syncAll(e.target.checked));
        sungaiAllInner.addEventListener('change', e => syncAll(e.target.checked));
    
        sungaiItems.forEach(cb => {
            cb.addEventListener('change', () => {
                const allChecked = Array.from(sungaiItems).every(c => c.checked);
                sungaiAllOuter.checked = allChecked;
                sungaiAllInner.checked = allChecked;
            });
        });
    });
    </script>
</body>

</html>