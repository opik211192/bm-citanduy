<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Citanduy</title>

    {{--
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{--
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
    <style>
        body,
        .content-wrapper,
        .main-sidebar,
        .main-header,
        .main-footer {
            font-family: 'Inter', sans-serif !important;
            font-size: 15px;
        }
    </style>
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Main Header -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.main-header -->

        <!-- Sidebar -->
        {{-- <aside class="main-sidebar sidebar-dark-primary elevation-4"> --}}
            <aside class="main-sidebar sidebar-light-primary elevation-4">
                <!-- Brand Logo -->
                <a href="#" class="brand-link text-center">
                    <img src="{{ asset('img/citanduy.png') }}" alt="Logo Citanduy"
                        class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">SIG Citanduy</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                            <!-- Home -->
                            <li class="nav-item">
                                <a href="{{ route('home') }}"
                                    class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-home"></i>
                                    <p>Home</p>
                                </a>
                            </li>

                            <!-- Dashboard -->
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}"
                                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>

                            <!-- Aset -->
                            <li class="nav-item">
                                <a href="{{ route('aset.index') }}"
                                    class="nav-link {{ request()->routeIs('aset.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>Aset</p>
                                </a>
                            </li>

                            <!-- Benchmark -->
                            <li class="nav-item">
                                <a href="{{ route('benchmark.index') }}"
                                    class="nav-link {{ request()->routeIs('benchmark.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-map"></i>
                                    <p>Benchmark</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- /.sidebar -->
            </aside>
            <!-- /.main-sidebar -->

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper pt-4">
                <!-- Main content -->
                <section class="content">

                    @yield('content')
                    <div class="row"></div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Main Footer -->
            <footer class="main-footer">
                <!-- To the right -->
                <div class="float-right d-none d-sm-inline">
                    {{-- Anything you want --}}
                </div>
                <!-- Default to the left -->
                <strong>Copyright &copy;
                    <?php echo date('Y'); ?> <a href="#">SISDA BBWS Citanduy</a>.
                </strong>
            </footer>
    </div>

    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/UTMLatLng.js') }}"></script>
    @stack('scripts')
</body>

</html>