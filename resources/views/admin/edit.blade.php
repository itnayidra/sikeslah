@extends('layout.mainapplogin')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        
        <!-- /.content-header -->
        <section class="content justify-content-center align-items-center vh-100">
            <div class="container-xl justify-content-center align-items-center">
                <div class="row justify-content-center">                
                    
                    <div class="col-md-8">
                        <!-- general form elements -->
                        <div class="card card-primary mt-5" style="margin-left:120px; font-family: Nunito;">
                            <div class="card-header text-center">
                                <h3 class="card-title" style="color:rgb(0, 0, 0); padding:10px">Form Edit User</h3>
                            </div>
                                <form action="{{ route('admin.user.update', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                        
                                <!-- Form Start -->
                                <div class="card-body">
                                    <!-- Email Input -->
                                    <div class="form-group mb-3 mt-1">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $data->email }}" placeholder="Masukkan Email" required>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
        
                                    <!-- Name Input -->
                                    <div class="form-group mb-3 mt-1">
                                        <label for="nama">Nama</label>
                                        <input type="text" name="nama" class="form-control" id="nama" value="{{ $data->name }}" placeholder="Masukkan Nama Lengkap" required>
                                        @error('nama')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
        
                                    <!-- Password Input -->
                                    <div class="form-group mb-3 mt-1">
                                        <label for="password">Kata Sandi</label>
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan Kata Sandi">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
        
                                    <!-- Role Input -->
                                    <div class="form-group mb-3 mt-1">
                                        <label for="role">Role</label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value="user" {{ $data->role == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="admin" {{ $data->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        @error('role')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Card Footer -->
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-success">Perbarui</button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-warning">Batal</a>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!--/.col (left) -->
                    </div>
                </form>
            </div><!-- /.container-fluid -->
        </section>
        

    </div>
@endsection
