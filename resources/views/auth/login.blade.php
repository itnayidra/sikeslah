    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiKeslah</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon-sikeslah.png') }}" sizes="32x32">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset ('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset ('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset ('lte/dist/css/adminlte.min.css') }}">

    <style>
        .card-primary.card-outline {
            border-top: 3px solid #495E57;
        }
    </style>
    </head>
    <body class="hold-transition login-page">
    <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="" class="h1" style="color:#262e2b;"><img src="/icon/logo_sikeslah.png" alt="" style="width: 65%">
            </a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Masuk untuk Evaluasi Kesesuaian Lahan!</p>

            <form action="{{ route('login_proses') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                @error('email')
                    <small>{{ $message }}</small>
                @enderror
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Kata Sandi">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                @error('password')
                    <small>{{ $message }}</small>
                @enderror
                <div class="row mb-3">
                    <div class="col-7">
                        <div class="back-to-home text-center">
                            <a href="/" class="btn btn-warning btn-block" >
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-5">
                        <button type="submit" class="btn btn-success btn-block">Masuk</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            
            <!-- /.social-auth-links -->

            {{-- <p class="mb-1">
                <a href="{{ route('forgot-password') }}">Lupa kata sandi?</a>
            </p> --}}
            <p class="mb-0">
                <a href="{{ route('register') }}" class="text-center">Daftar sebagai pengguna baru</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js')}}"></script>
    {{-- Sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                icon: "success",
                text: "Kamu berhasil keluar!",
                showConfirmButton: false,
                });
        </script>
    @endif
    @if ($message = Session::get('Gagal'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Email atau Kata Sandi Salah",
                });
        </script>
    @endif
</body>
</html>
