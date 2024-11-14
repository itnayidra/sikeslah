<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    <title img="{{ asset('/icon/icon-sikeslah.png') }}">SiKeslah</title>

    @yield('head')  

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('/icon/icon-sikeslah.png') }}" sizes="32x32">

    {{-- <link href="assets/img/icon-sikeslah.png" rel="icon"> --}}
    {{-- <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon"> --}}

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" >

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- jQuery --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.semanticui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/5.0.3/css/fixedColumns.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.css">

    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Google Font --}}
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    {{-- SweetAlert --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    @vite(['resources/css/mainapp.css', 'resources/js/mainapp.js'])

</head>
<body class="index-page">
    <header id="header" class="header navbar-expand-lg bg-body-tertiary d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center me-auto me-lg-0"><img src="{{ asset('/icon/logo_sikeslah.png') }}" alt=""> </a>
            @else
                <a href="{{ route('user.dashboard') }}" class="logo d-flex align-items-center me-auto me-lg-0"><img src="{{ asset('/icon/logo_sikeslah.png') }}" alt=""> </a>
            @endif
            {{-- <a href="home" class="logo d-flex align-items-center me-auto me-lg-0"> --}}
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="assets/img/logo.png" alt=""> -->
                {{-- <h1 class="sitename"><strong>Si</strong>Keslah</h1>
                <span>.</span> --}}
                {{-- <img src="/icon/logo_sikeslah.png" alt=""> --}}
            {{-- </a> --}}
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="nav-link">Dashboard</a>
                        @endif
                    </li>
                    <li class="dropdown"><a href="#services"><span>Layanan</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.lbsmap') }}" class="nav-link">Peta Lahan Sawah</a>
                                @else
                                    <a href="{{ route('user.lbsmap') }}" class="nav-link">Peta Lahan Sawah</a>
                                @endif
                                {{-- <a href="{{ route('lbsmap') }}">Peta Lahan Sawah</a></li> --}}
                            </li>
                            <li>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.keslahmap') }}" class="nav-link">Cek Kesesuaian Lahan</a>
                                @else
                                    <a href="{{ route('user.keslahmap') }}" class="nav-link">Cek Kesesuaian Lahan</a>
                                @endif
                                {{-- <a href="{{ route('keslahmap') }}">Cek Kesesuaian Lahan</a></li> --}}
                            </li>
                            <li>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.add-area') }}" class="nav-link">Nilai Kesesuaian Lahan</a>
                                @else
                                    <a href="{{ route('user.add-area') }}" class="nav-link">Nilai Kesesuaian Lahan</a>
                                @endif
                                {{-- <a href="{{ route('add-area') }}">Nilai Kesesuaian Lahan</a></li> --}}
                            </li>   
                        </ul>
                    </li>
                    <li>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.panduan') }}" class="nav-link">Panduan</a>
                        @else
                            <a href="{{ route('user.panduan') }}" class="nav-link">Panduan</a>
                        @endif
                    </li>
                    <li>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.info') }}" class="nav-link">Info</a>
                        @else
                            <a href="{{ route('user.info') }}" class="nav-link">Info</a>
                        @endif
                    </li>
                </ul>                   
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
            <a class="btn-getstarted" href="/logout" id="keluar" style="color:black"><b>Keluar</b></a>
        </div>
    </header>

    @yield('content')
    <footer id="footer" class="footer dark-background">

        <div class="copyright">
        <div class="container text-center">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">2024</strong></p>
            <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you've purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
            Dibuat oleh Nisa Ardiyanti | D4 Sistem Informasi Geografis
            </div>
        </div>
    </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.semanticui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.js"></script>    
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.3/js/dataTables.fixedColumns.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.3/js/fixedColumns.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.colVis.min.js"></script>    
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.dataTables.js"></script>
    {{-- ChartJS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!--Terraformer-->
    <script src="https://cdn.jsdelivr.net/npm/terraformer@1.0.12/terraformer.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/terraformer-wkt-parser@1.2.1/terraformer-wkt-parser.min.js"></script>

    {{-- Sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('/build/assets/mainapp-DiOVHxvf.js') }}"></script>


    @yield('scripts')
    
    <script>
        $(document).ready(function() {
        $('#keluar').on('click', function(e) {
            e.preventDefault(); // Mencegah link untuk langsung mengarahkan

            Swal.fire({
                title: "Apakah Anda yakin ingin keluar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, keluar",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, redirect ke halaman logout
                    window.location.href = "/logout";
                }
            });
        });
    });

    </script>
</body>
</html>