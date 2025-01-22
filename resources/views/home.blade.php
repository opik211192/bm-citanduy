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
            top: 56px;
            bottom: 0;
            width: 250px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            /* Add shadow */
            border-radius: 0 10px 10px 0;
            /* Rounded corners */
            overflow-y: auto;
            z-index: 9999;
            transition: left 0.3s ease;
            left: -250px;
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
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .mini-menu {
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: auto;
            background-color: #007bff;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 10000;
            padding: 5px;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
        }

        .mini-menu div {
            padding: 10px 5px;
            font-size: 18px;
        }

        .leaflet-control-zoom {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
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

        /* Media Query for small screens */
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

            .mini-menu {
                transition: opacity 0.3s;
            }
        }
    </style>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body>
    <div class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <div class="d-flex">
                    <img src="{{ asset('img/citanduy.png') }}" alt="logo citanduy" width="50px" height="35px"
                        class="me-2">
                    <div>B</div>
                    <div>M</div>
                    <div>&nbsp;Citanduy</div>
                </div>
            </a>

            <!-- Toggle button for mobile -->
            <div class="toggle-btn" id="toggle-btn">&#9776;</div>

            <!-- Navbar content -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">&#9776;</span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        @if (Route::has('login'))
                        <div>
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

    <!-- Mini Menu (Side Buttons) -->
    <div class="mini-menu" onclick="toggleSidebar()">
        <div>&#9776;</div>
    </div>

    <!-- Left Sidebar -->
    <div class="sidebar left" id="sidebar">
        <div class="sidebar-header">
            <h3 class="text-center mb-3 fw-bold">Menu</h3>
            <div class="btn-close" id="close-btn" onclick="toggleSidebar()"></div>
        </div>
        <br>
        <div class="row">
            <div class="col mb-2">
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Benchmark
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo"
                        data-bs-parent="#accordionExample">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="checkedbm">
                                <label class="form-check-label" for="checkedbm">
                                    BM Citanduy
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="sidebar right" id="sidebar-right">
        <div class="sidebar-header">
            <h5 class="text-center mb-3 fw-bold">Detail</h5>
            <div class="btn-close" id="close-btn-right" onclick="toggleDetailSidebar()"></div>
        </div>
        <div id="detail-content">
            <!-- Detail content will be loaded here -->
        </div>
    </div>

    <!-- Map Container -->
    <div id="map">
        <div id="spinner" style="display: none;">
            <div class="spinner-grow" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Custom JavaScript for Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const miniMenu = document.querySelector('.mini-menu');
            sidebar.classList.toggle('active');

            if (sidebar.classList.contains('active')) {
                miniMenu.style.display = 'none';
            } else {
                miniMenu.style.display = 'flex';
            }
        }

        function toggleDetailSidebar() {
            const sidebarRight = document.getElementById('sidebar-right');
            sidebarRight.classList.toggle('active');
        }

        // Event Listener untuk membuka sidebar
        document.getElementById('toggle-btn').addEventListener('click', toggleSidebar);

        // Event Listener untuk membuka sidebar kanan
        document.getElementById('detail-btn').addEventListener('click', toggleDetailSidebar);
    </script>

    <script src="{{ asset('js/map.js') }}"></script>
    <script src="{{ asset('js/detail.js') }}"></script>
</body>

</html>