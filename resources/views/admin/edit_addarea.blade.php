@extends('layout.mainapplogin')

@section('head')
{{-- @vite(['resources/css/evaluasipage.css', 'resources/js/evaluasipage.js']) --}}
<link rel="stylesheet" href="{{ asset('/build/assets/evaluasipage-BJJjRMGo.css') }}">

<style>
    body {
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
                            <form id="formUpdate" action="{{ route('admin.updateaddarea', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data" style="font-family: 'Nunito'; font-size: 14px; background-color: #f9f9f9; border-radius: 8px; padding: 10px 20px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                                @csrf
                                @method('PUT')
                                <h3 class="text-center" style="margin-bottom: 10px; margin-top: 10px; color: #333;">Edit Data</h3>
                                <div class="form-group mb-3">
                                    <label for="nama_area" class="form-label">Nama Area</label>
                                    <input type="text" class="form-control" id="nama_area" name="nama_area" value="{{ $data->nama_area }}" placeholder="Masukkan nama area">
                                    @error('nama_area')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="{{ $data->deskripsi}}" placeholder="Masukkan nama area">
                                    @error('deskripsi')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="area" class="form-label">Koordinat Lokasi</label>
                                    <textarea class="form-control" id="area" name="area">{{ isset($data->area_wkt) ? $data->area_wkt : 'POLYGON(())' }}</textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="luas" class="form-label">Luas Area (m²)</label>
                                    <input type="text" class="form-control" id="luas" name="luas" {{-- value="{{ number_format($data->luas, 2, ',', '.') --}} value="{{ $data->luas}}" readonly>
                                    @error('luas')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <h4 style="margin: 20px 0 10px;">Nilai Parameter</h4>
                                {{-- <h5>Temperatur (°C)</h5> --}}
                                {{-- Temp --}}
                                <div class="form-group mb-3">
                                    <label for="temperatur">Temperatur</label>
                                    <input type="number" step="0.01" class="form-control" id="temperatur" name="temperatur" value="{{ $data->temperatur }}" >
                                    @error('temperatur')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                
                                {{-- CH --}}
                                <div class="form-group mb-3">
                                    <label for="bulan_basah" class="form-label">Jumlah Bulan Basah dalam 1 Tahun</label>
                                    <input type="number" step="0.01" class="form-control" id="bulan_basah" name="bulan_basah" value="{{ $data->bulan_basah }}">
                                    @error('bulan_basah')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                {{-- Drainase --}}
                                <div class="form-group mb-3">
                                    <label for="drainase" class="form-label">Drainase</label>
                                    <select id="drainase" class="form-select" name="drainase" aria-label="Default select example">
                                        <option disabled {{ !$data->drainase ? 'selected' : '' }}>Pilih jenis drainase</option>
                                        <option value="Sangat terhambat" {{ $data->drainase == 'Sangat terhambat' ? 'selected' : '' }}>Sangat Terhambat</option>
                                        <option value="Terhambat" {{ $data->drainase == 'Terhambat' ? 'selected' : '' }}>Terhambat</option>
                                        <option value="Agak terhambat" {{ $data->drainase == 'Agak terhambat' ? 'selected' : '' }}>Agak Terhambat</option>
                                        <option value="Agak baik" {{ $data->drainase == 'Agak baik' ? 'selected' : '' }}>Agak Baik</option>
                                        <option value="Baik" {{ $data->drainase == 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Agak cepat" {{ $data->drainase == 'Agak cepat' ? 'selected' : '' }}>Agak Cepat</option>
                                        <option value="Cepat" {{ $data->drainase == 'Cepat' ? 'selected' : '' }}>Cepat</option>
                                    </select> 
                                    @error('drainase')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                
                                
                                {{-- <h5>Media Perakaran</h5> --}}
                                {{-- Tekstur --}}
                                <div class="form-group mb-3">
                                    <label for="tekstur" class="form-label">Tekstur</label>
                                        <select id="tekstur" class="form-select" name="tekstur" aria-label="Default select example">
                                            <option disabled {{ !$data->tekstur ? 'selected' : '' }}>Pilih jenis tekstur</option>
                                            <option value="Sangat halus" {{ $data->tekstur == 'Sangat halus' ? 'selected' : '' }}>Sangat Halus</option>
                                            <option value="Halus" {{ $data->tekstur == 'Halus' ? 'selected' : '' }}>Halus</option>
                                            <option value="Agak halus" {{ $data->tekstur == 'Agak halus' ? 'selected' : '' }}>Agak Halus</option>
                                            <option value="Sedang" {{ $data->tekstur == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                            <option value="Agak kasar" {{ $data->tekstur == 'Agak kasar' ? 'selected' : '' }}>Agak Kasar</option>
                                            <option value="Kasar" {{ $data->tekstur == 'Kasar' ? 'selected' : '' }}>Kasar</option>
                                        </select>                                        
                                    @error('tekstur')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                {{-- Kedalaman Tanah --}}
                                <div class="form-group mb-3">
                                    <label for="kedalaman_tanah" class="form-label">Kedalaman Tanah</label>
                                    <input type="number" step="0.01" class="form-control" id="kedalaman_tanah" name="kedalaman_tanah" value="{{ $data->kedalaman_tanah}}">
                                    {{-- 
                                    <select id="kedalaman_tanah" class="form-select" name="kedalaman_tanah" aria-label="Default select example">
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
                                    <input type="number" step="0.01" class="form-control" id="kejenuhan_basa" name="kejenuhan_basa" value="{{ $data->kejenuhan_basa}}">
                                    {{-- 
                                    <select id="kejenuhan_basa" class="form-select" name="kejenuhan_basa" aria-label="Default select example">
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
                                    <input type="text" class="form-control" id="ph" name="ph" value="{{ $data->ph}}">
                                    @error('ph')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                {{-- KTK-liat --}}
                                <div class="form-group mb-3">
                                    <label for="ktk" class="form-label">KTK Liat</label>
                                    <input type="number" step="0.01" class="form-control" id="ktk" name="ktk" value="{{ $data->ktk}}">
                                    @error('ktk')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                {{-- c-organik --}}
                                <div class="form-group mb-3">
                                    <label for="c_organik" class="form-label">c-organik</label>
                                    <input type="number" step="0.01" class="form-control" id="c_organik" name="c_organik" value="{{ $data->c_organik }}">

                                    {{-- 
                                    <select id="c_organik" class="form-select" name="c_organik" aria-label="Default select example">
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
                                        <select id="erosi" class="form-select" name="erosi" aria-label="Default select example">
                                            <option disabled {{ !$data->erosi ? 'selected' : '' }}>Pilih tingkat bahaya erosi</option>
                                            <option value="Tidak ada" {{ $data->erosi == 'Tidak ada' ? 'selected' : '' }}>Tidak ada</option>
                                            <option value="Sangat ringan" {{ $data->erosi == 'Sangat ringan' ? 'selected' : '' }}>Sangat rendah</option>
                                            <option value="Ringan" {{ $data->erosi == 'Ringan' ? 'selected' : '' }}>Rendah</option>
                                            <option value="Sedang" {{ $data->erosi == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                            <option value="Berat" {{ $data->erosi == 'Berat' ? 'selected' : '' }}>Berat</option>
                                            <option value="Sangat berat" {{ $data->erosi == 'Sangat berat' ? 'selected' : '' }}>Sangat berat</option>
                                        </select>                                        
                                    @error('erosi')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                {{-- Lereng --}}
                                <div class="form-group mb-3">
                                    <label for="lereng" class="form-label">Lereng</label>
                                        <select id="lereng" class="form-select" name="lereng" aria-label="Default select example">
                                            <option disabled {{ !$data->lereng ? 'selected' : '' }}>Pilih kelas lereng</option>
                                            <option value="Datar" {{ $data->lereng == 'Datar' ? 'selected' : '' }}>Datar</option>
                                            <option value="Landai" {{ $data->lereng == 'Landai' ? 'selected' : '' }}>Landai</option>
                                            <option value="Agak landai" {{ $data->lereng == 'Agak landai' ? 'selected' : '' }}>Agak landai</option>
                                            <option value="Agak curam" {{ $data->lereng == 'Agak curam' ? 'selected' : '' }}>Agak curam</option>
                                            <option value="Curam" {{ $data->lereng == 'Curam' ? 'selected' : '' }}>Curam</option>
                                            <option value="Sangat curam" {{ $data->lereng == 'Sangat curam' ? 'selected' : '' }}>Sangat curam</option>
                                        </select>                                        
                                    @error('lereng')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                
                                
                                {{-- <h5>Bahaya Banjir</h5> --}}
                                {{-- Banjir --}}
                                <div class="form-group mb-3">
                                    <label for="banjir" class="form-label">Bahaya Banjir</label>
                                        <select id="banjir" class="form-select" name="banjir" aria-label="Default select example">
                                            <option disabled {{ !$data->banjir ? 'selected' : '' }}>Pilih rentang tekstur</option>
                                            <option value="Tidak ada" {{ $data->banjir == 'Tidak ada' ? 'selected' : '' }}>Tidak ada banjir di dalam periode satu tahun</option>
                                            <option value="Rendah" {{ $data->banjir == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                            <option value="Sedang" {{ $data->banjir == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                            <option value="Tinggi" {{ $data->banjir == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                            <option value="Sangat tinggi" {{ $data->banjir == 'Sangat tinggi' ? 'selected' : '' }}>Sangat tinggi</option>
                                        </select>
                                    @error('banjir')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="card-footer mt-4 text-end">
                                    <button type="submit" id="perbarui" class="btn btn-success me-2">
                                        Perbarui
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-warning"  id="btn_cancel_polygon">Batal</a>
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
    </section>
</div>

@endsection

@section('scripts')
<script type="module" scr="{{ asset('/build/assets/edit_evaluasipage-D984gYnN.js') }}"></script>

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
        $(document).ready(function() {            
            $('#perbarui').on('click', function(e){
                e.preventDefault();
                Swal.fire({
                title: "Apakah Anda ingin menyimpan perubahan?",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Simpan",
                denyButtonText: `Tidak`,
                cancelButtonText: "Batal"
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('#formUpdate').submit(); 
                    Swal.fire({
                        title: "Data terbaru tersimpan!",
                        icon: "success"
                    }).then(() => {
                        // Redirect to admin.dashboard after successful save
                        window.location.href = "{{ route('admin.dashboard') }}";
                    });
                } else if (result.isDenied) {
                    Swal.fire("Perubahan data tidak disimpan", "", "info");
                }
                });
            });
        })
    </script>
@endsection