<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <style>
        body {
            background-color: white;
            margin: 0;
            padding: 0;
        }

        #map {
            width: 100%;
            height: calc(100vh - 56px);
            padding-top: 56px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            /* Mengatur jarak antara navbar-brand dan navbar links */
            align-items: center;
            /* Mengatur posisi vertikal tombol toggle */
            z-index: 10000;
        }

        .toggle-btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
            transition: right 0.3s ease;
        }

        .toggle-btn.active {
            right: 0;
        }

        .navbar-collapse {
            display: flex;
            justify-content: flex-end;
            /* Mengatur posisi navbar links di sebelah kanan */
        }

        .navbar-nav {
            margin-right: 20px;
            /* Mengatur jarak antara navbar links dan tombol toggle */
        }

        .navbar-toggler {
            order: -1;
            /* Mengatur urutan tombol toggle agar berada di sebelah kanan */
        }

        .sidebar {
            position: fixed;
            top: 56px;
            left: -250px;
            bottom: 0;
            width: 250px;
            padding: 20px;
            background-color: #f8f9fa;
            overflow-y: auto;
            z-index: 9999;
            transition: left 0.3s ease;
        }

        .sidebar.active {
            left: 0;
        }

        .leaflet-control-zoom {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }
    </style>
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body>
    <div class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">BM Citanduy</a>
            <div class="toggle-btn" id="toggle-btn">&#9776;</div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <li class="nav-item">
                        @if (Route::has('login'))
                        <div class="">
                            @auth
                            <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                            @else
                            <a href="{{ route('login') }}" class="nav-link">Log in</a>
                            @endauth
                        </div>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="sidebar active" id="sidebar">
        <h3>Sidebar</h3>
        <ul class="list-unstyled">
            <li><a href="#">Menu 1</a></li>
            <li><a href="#">Menu 2</a></li>
            <li><a href="#">Menu 3</a></li>
            <li><a href="#">Menu 4</a></li>
        </ul>
    </div>
    <div id="map"></div>

    <script>
        document.getElementById('toggle-btn').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
            this.classList.toggle('active');
        });
    </script>

    <script>
        // Inisialisasi peta
        var mymap = L.map('map').setView([-7.2098686, 108.237827], 9);
    
        // Tambahkan layer tile
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mymap);

       L.control.zoom({
    position: 'topright'
    }).addTo(mymap);
    </script>
</body>

</html>