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
  <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

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
      <a href="{{ route('register') }}" class="h1"style="color:#262e2b;"><img src="/icon/logo_sikeslah.png" alt="" style="width: 65%"></a>
    </div>
    <div class="card-body">
      {{-- <p class="login-box-msg">Daftar</p> --}}

      <form action="{{ route('register_proses') }}" method="post">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" value="{{ old('nama') }}">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>

          </div>
          @error('email')
              <small>{{ $message }}</small>
            @enderror
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
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
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>

        </div>
        @error('password')
            <small>{{ $message }}</small>
          @enderror
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-success btn-block">Daftar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-2">
        <a href="{{ route('login') }}" class="text-center">Sudah punya akun? Masuk</a>
    </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if($message = Session::get('success'))
    <script>
        Swal.fire('{{ $message }}');
    </script>
@endif

@if($message = Session::get('failed'))
    <script>
        Swal.fire('{{ $message }}');
    </script>
@endif
</body>
</html>
