@extends('layout.mainapplogin')
@section('head')
<style>
    section.content {
        height: 100vh; /* Ensure the section fills the full height of the screen */
    }

    .card {
        max-width: 600px; /* Set a max width for the form card */
        width: 100%; /* Ensure it uses full width for smaller screens */
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    {{-- <!-- Content Header (Page header) -->
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="color:white; style:text-align:center; padding:10px">Tambah Pengguna</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header --> --}}

    <!-- Main content -->
    <section class="content justify-content-center align-items-center vh-100">
        <div class="container-xl justify-content-center align-items-center">
            <div class="row justify-content-center">
                <!-- Make the column width larger (col-md-8 or 10 for larger size) -->
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card card-primary mt-5" style="margin-left:120px;font-family: Nunito;">
                        <div class="card-header text-center">
                            <h3 class="card-title" style="color:rgb(0, 0, 0); padding:10px">Form Tambah User</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('admin.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group mb-3 mt-1">
                                    <label for="exampleInputEmail1">Email</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-3 mt-1">
                                    <label for="exampleInputNama1">Nama</label>
                                    <input type="text" class="form-control" id="exampleInputName1" name="name" placeholder="Enter name">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-3 mt-1">
                                    <label for="exampleInputPassword1">Kata Sandi</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->
    
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-success">Simpan</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-warning">Batal</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>    
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    
    <!-- /.content -->
</div>
@endsection

@section('scripts')
@endsection