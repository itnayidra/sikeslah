@extends('layout.mainapplogin')

@section('head')
    {{-- @vite(['resources/css/evaluasipage.css', 'resources/js/evaluasipage.js']) --}}
    <link rel="stylesheet" href="{{ asset('/build/assets/evaluasipage-BJJjRMGo.css') }}">

    <style>
        body{
      padding-top: 50px;
      overflow-x: hidden;
      font-family: "Open Sans", sans-serif;
      background-color: #495E57;
    }
    </style>
@endsection

@section('content')
<div class="content-wrapper mt-5">
    <!-- Content Header (Page header) -->
    {{-- <div class="content-header"> --}}
        {{-- <div class="container-fluid"> --}}
        {{-- <div class="row mb-2"> --}}
            {{-- <div class="col-sm-6"> --}}
            {{-- <h1 class="m-0" style="color:white">Evaluasi Kesesuaian Lahan</h1> --}}
            {{-- </div><!-- /.col --> --}}
        {{-- </div><!-- /.row --> --}}
        {{-- </div><!-- /.container-fluid --> --}}
    {{-- </div> --}}
    <!-- /.content-header -->

  <!-- Main content -->
  <section class="content" style="padding: 10px 0;">
    <div class="container-fluid">
        <div class="card mt-5 mb-3">
            {{-- <div class="card-header text-center">
                <h3>Evaluasi Kesesuaian Lahan</h3>
            </div> --}}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <div id="map" class="map">
                            <div id="scale_bar"></div>
                            <div id="scale_bar1"></div>
                            <div class="zoom-button">
                                <button type="button" id="zoomin" class="btn-zoomin" data-bs-toggle="tooltip" data-bs-placement="top" title="Perbesar tampilan peta"><i class="fa-solid fa-plus"></i></button>
                                <button type="button" id="zoomout" class="btn-zoomout" data-bs-toggle="tooltip" data-bs-placement="top" title="Perkecil tampilan peta"><i class="fa-solid fa-minus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 px-3">
                        <form id="landForm" action="{{ route('store_area') }}" method="post"  style="font-family: 'Nunito'; font-size: 14px; background-color: #f9f9f9; border-radius: 8px; padding: 10px 20px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                            @csrf
                            <h3 class="text-center" style="margin-bottom: 10px; margin-top: 10px; color: #333;">Lengkapi Data</h3>
                            <div class="form-group mb-3">
                                <label for="nama_area" class="form-label">Nama Area</label>
                                <input type="text" class="form-control" id="nama_area" name="nama_area" placeholder="Masukkan nama area">
                                @error('nama_area')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <input type="text" class="form-control" id="deskripsi" name="deskripsi" placeholder="Tambahkan deskripsi area">
                                @error('deskripsi')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="area" class="form-label">Koordinat Lokasi</label>
                                <textarea class="form-control" id="area" name="area"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="luas" class="form-label">Luas Area (m²)</label>
                                <input type="text" class="form-control" id="luas" name="luas" readonly>
                                 @error('luas')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            
                            <h4 style="margin: 20px 0 10px;">Nilai Parameter</h4>
                            {{-- Temp --}}
                            <div class="form-group mb-3">
                                <label for="temperatur">Temperatur</label>
                                <input type="number" step="0.01" class="form-control" id="temperatur" name="temperatur" placeholder="°C">
                                 @error('temperatur')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            {{-- CH --}}
                            <div class="form-group mb-3">
                                <label for="bulan_basah" class="form-label">Jumlah Bulan Basah dalam 1 Tahun</label>
                                <input type="number" step="0.01" class="form-control" id="bulan_basah" name="bulan_basah" placeholder="Masukkan jumlah bulan hujan (1 tahun)">
                                 @error('bulan_basah')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            {{-- Drainase --}}
                            <div class="form-group mb-3">
                                <label for="drainase" class="form-label">Drainase</label>
                                    <select id="drainase" class="form-select" name="drainase" placeholder="Pilih tingkat drainase">
                                        <option selected>Pilih jenis drainase</option>
                                        <option value="Sangat terhambat">Sangat Terhambat</option>
                                        <option value="Terhambat">Terhambat</option>
                                        <option value="Agak terhambat">Agak Terhambat</option>
                                        <option value="Agak baik">Agak Baik</option>
                                        <option value="Baik">Baik</option>
                                        <option value="Agak cepat">Agak Cepat</option>
                                        <option value="Cepat">Cepat</option>
                                    </select>
                                 @error('drainase')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            
                            {{-- Tekstur --}}
                            <div class="form-group mb-3">
                                <label for="tekstur" class="form-label">Tekstur</label>
                                    <select id="tekstur" class="form-select" name="tekstur" placeholder="Pilih tekstur tanah">
                                        <option selected>Pilih jenis tekstur</option>
                                        <option value="Sangat halus">Sangat Halus</option>
                                        <option value="Halus">Halus</option>
                                        <option value="Agak halus">Agak Halus</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Agak kasar">Agak Kasar</option>
                                        <option value="Kasar">Kasar</option>
                                    </select>
                                 @error('tekstur')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
        
                            {{-- Kedalaman Tanah --}}
                            <div class="form-group mb-3">
                                <label for="kedalaman_tanah" class="form-label">Kedalaman Tanah</label>
                                <input type="number" step="0.01" class="form-control" id="kedalaman_tanah" name="kedalaman_tanah" placeholder="Masukkan nilai kedalaman tanah">
                                {{-- <div class="col-sm-10">
                                    <select id="kedalaman_tanah" class="form-select" name="kedalaman_tanah">
                                        <option selected>Pilih jenis tekstur</option>
                                        <option value="Sangat dangkal">Sangat dangkal</option>
                                        <option value="Dangkal">Dangkal</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Dalam">Dalam</option>
                                    </select>
                                </div> --}}
                                 @error('kedalaman_tanah')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            
                            {{-- <h5>Retensi Hara</h5> --}}
                            {{-- Kejenuhan Basa --}}
                            <div class="form-group mb-3">
                                <label for="kejenuhan_basa" class="form-label">Kejenuhan Basa</label>
                                <input type="number" step="0.01" class="form-control" id="kejenuhan_basa" name="kejenuhan_basa" placeholder="Masukkan nilai kejenuhan basa">
                                {{-- <div class="col-sm-10">
                                    <select id="kejenuhan_basa" class="form-select" name="kejenuhan_basa">
                                        <option selected>Pilih jenis tekstur</option>
                                        <option value="Tinggi">Tanah Sangat Subur</option>
                                        <option value="Sedang">Tanah Cukup Subur</option>
                                        <option value="Rendah">Tanah Kurang Subur</option>
                                    </select>
                                </div> --}}
                                 @error('kejenuhan_basa')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            
                            {{-- pH H2O --}}
                            <div class="form-group mb-3">
                                <label for="ph" class="form-label">pH H2O</label>
                                <input type="text" class="form-control" id="ph" name="ph" placeholder="Masukkan nilai ph H2O">
                                 @error('ph')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            {{-- KTK-liat --}}
                            <div class="form-group mb-3">
                                <label for="ktk" class="form-label">KTK Liat</label>
                                <input type="number" step="0.01" class="form-control" id="ktk" name="ktk" placeholder="Masukkan nilai ktk-liat">
                                 @error('ktk')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
        
                            {{-- c-organik --}}
                            <div class="form-group mb-3">
                                <label for="c_organik" class="form-label">c-organik</label>
                                <input type="number" step="0.01" class="form-control" id="c_organik" name="c_organik" placeholder="Masukkan nilai c-organik">

                                {{-- <div class="col-sm-10">
                                    <select id="c_organik" class="form-select" name="c_organik">
                                        <option selected>Pilih rentang tekstur</option>
                                        <option value="S1">>1.5</option>
                                        <option value="S2">0.8-1.5</option>
                                        <option value="S3"><=0.8</option>
                                        <option value="N"></option>
                                    </select>
                                </div> --}}
                                 @error('c_organik')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
        
                            
                            {{-- <h5>Bahaya Erosi</h5> --}}
                            {{-- Bahaya erosi --}}
                            <div class="form-group mb-3">
                                <label for="erosi" class="form-label">Bahaya Erosi</label>
                                    <select id="erosi" class="form-select" name="erosi" placeholder="Pilih tingkat bahaya erosi">
                                        <option selected>Pilih tingkat bahaya erosi</option>
                                        <option value="Tidak ada">Tidak ada</option>
                                        <option value="Sangat ringan">Sangat rendah</option>
                                        <option value="Ringan">Rendah</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Berat">Berat</option>
                                        <option value="Sangat berat">Sangat berat</option>
                                    </select>
                                 @error('erosi')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
        
                            {{-- Lereng --}}
                            <div class="form-group mb-3">
                                <label for="lereng" class="form-label">Lereng</label>
                                    <select id="lereng" class="form-select" name="lereng" placeholder="Pilih tingkat kemiringan lereng">
                                        <option selected>Pilih kelas lereng</option>
                                        <option value="Datar">Datar</option>
                                        <option value="Landai">Landai</option>
                                        <option value="Agak landai">Agak landai</option>
                                        <option value="Agak curam">Agak curam</option>
                                        <option value="Curam">Curam</option>
                                        <option value="Sangat curam">Sangat curam</option>
                                    </select>
                                 @error('lereng')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            
                            {{-- <h5>Bahaya Banjir</h5> --}}
                            {{-- Banjir --}}
                            <div class="form-group mb-3">
                                <label for="banjir" class="form-label">Bahaya Banjir</label>
                                    <select id="banjir" class="form-select" name="banjir" placeholder="Pilih tingkat bahaya banjir">
                                        <option selected>Pilih tingkat bahaya banjir</option>
                                        <option value="Tidak ada">Tidak ada banjir di dalam periode satu tahun</option>
                                        <option value="Rendah">Rendah</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Tinggi">Tinggi</option>
                                        <option value="Sangat tinggi">Sangat tinggi</option>
                                    </select>
                                 @error('banjir')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <br>
                            <div class="card-footer mt-4 text-end">
                                <button type="submit" class="btn btn-primary me-2">Evaluasi</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" id="btn_cancel_polygon">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Hasil Evaluasi Kesesuaian Lahan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-evaluation-results">
                {{-- Hasil Kesesuaian Lahan --}}
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close-modal-results">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
            </div>
        </div>
        
      <!-- /.row -->
    </div><!-- /.container-fluid -->
    
@endsection

@section('scripts')
<script type="module" src="{{ asset('/build/assets/evaluasipage-B-oLlyqd.js') }}"></script>

<script>
    //  const resultButton = document.getElementById('result');
    // if (resultButton) {
    //     resultButton.addEventListener('click', () => {
    //         const { temperature, drainage } = evaluateSuitability();
    //         displayEvaluationResults(temperature, drainage);
    //     });
    // } else {
    //     console.error("Element with id 'result' not found");
    // }


</script>
@endsection