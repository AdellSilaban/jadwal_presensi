<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LPKKSK UKDW</title>

    <!-- Fonts & Icons -->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    
    <!-- Styles -->
    <link href="{{ asset('assets/css/sb-admin-2.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Fix Footer CSS -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        #wrapper {
            flex: 1;
            display: flex;
            flex-direction: row;
        }

        #content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        #content {
            flex: 1;
        }

        footer {
            background: white;
        }
    </style>
</head>

<body id="page-top">

    <!-- Wrapper: Sidebar + Main Content -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-text mx-3">LPKKSK UKDW</div>
            </a>
            <hr class="sidebar-divider" />
            <li class="nav-item">
                @include('layout.sidebar')
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Main Content Area -->
        <div id="content-wrapper">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                @include('layout.topbar')
            </nav>

            <!-- Main Content -->
            <div id="content">
                <div class="container-fluid">
                    @include('layout.content')
                </div>
            </div>
            <!-- End Main Content -->

            <!-- Footer -->
            <footer class="py-3 shadow-sm">
                <div class="container text-center">
                    <span class="text-muted small">
                        &copy; {{ now()->year }} Lembaga Pelayanan Kerohanian, Konseling, dan Spiritualitas Kampus
                    </span>
                </div>
            </footer>
        </div>
        <!-- End Content Wrapper -->
    </div>
    <!-- End Wrapper -->

    <!-- Scroll to Top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Scripts -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
