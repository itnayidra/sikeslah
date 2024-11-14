@extends('layout.mainapplogin')

@section('head')
<link rel="stylesheet" href="{{ asset('/build/assets/map-BDfcHxwu.css') }}">
<link rel="stylesheet" href="{{ asset('/build/assets/mappage-CqyiwhDG.css') }}">

{{-- @vite(['resources/css/mappage.css', 'resources/js/mappage.js', 'resources/css/map.css', 'resources/js/map.js']) --}}
<style>
    .toast-notification {
    visibility: hidden;
    min-width: 250px;
    background-color: #fff; /* Ubah latar belakang menjadi putih */
    color: #333; /* Teks berwarna gelap */
    text-align: center;
    border-radius: 5px;
    padding: 16px;
    position: fixed;
    z-index: 1;
    left: 50%;  /* Posisikan di tengah secara horizontal */
    top: 50%;   /* Posisikan di tengah secara vertikal */
    transform: translate(-50%, -50%); /* Pastikan posisi tengah sempurna */
    font-size: 17px;
    opacity: 0;
    transition: opacity 0.6s, visibility 0.6s;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan */
}

.toast-notification.show {
    visibility: visible;
    opacity: 1;
}


</style>
@endsection

@section('content')
<main id="main" class="main">
    {{-- PETA --}}
    <div id="map" class="map">
        <div id="scale_bar"></div>
        <div id="scale_bar1"></div>
        <div class="base-button">
            <button type="button" id="base" class="btn-base" data-bs-toggle="tooltip" data-bs-placement="top" title="Pusatkan peta"><i class="fa-solid fa-house"></i></button>                
        </div>  
        <div class="zoom-button">
            <button type="button" id="zoomin" class="btn-zoomin" data-bs-toggle="tooltip" data-bs-placement="top" title="Perbesar tampilan peta"><i class="fa-solid fa-plus"></i></button>
            <button type="button" id="zoomout" class="btn-zoomout" data-bs-toggle="tooltip" data-bs-placement="top" title="Perkecil tampilan peta"><i class="fa-solid fa-minus"></i></button>
        </div>
        <div class="cursor-button">
            <button type="button" id="cursor" class="btn-cursor" data-bs-toggle="tooltip" data-bs-placement="top" title="Kursor"><i class="fa-solid fa-arrow-pointer"></i></button>                
        </div>        
        <div class="measure-button">
            <button type="button" id="measure" class="btn-measure" data-bs-toggle="tooltip" data-bs-placement="top" title="Hitung luas"><i class="fa-solid fa-ruler-combined"></i></button>                
        </div>
        <div class="info-button">
            <button type="button" id="info" class="btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Info"><i class="fa-solid fa-info"></i></button>                
        </div>
    
        <div class="search-button">
            <button type="button" id="search" class="btn-search" data-bs-toggle="tooltip" data-bs-placement="top" title="Cari lokasi"><i class="fa-solid fa-magnifying-glass"></i></button>                
        </div>
        <div class="diagram-button">
            <button type="button" id="diagram" class="btn-diagram" data-bs-toggle="tooltip" data-bs-placement="top" title="Tampilkan diagram"><i class="fas fa-chart-pie"></i></button>                
        </div>
        <div class="unduhGeoJSON-button">
            <button type="button" id="unduhGeoJSON" class="btn-unduhGeoJSON" data-bs-toggle="tooltip" data-bs-placement="top" title="Unduh data"><i class="fa-solid fa-download"></i></button>                
        </div>
        <div id="filterControl" class="filter-button">
            <button type="button" class="btn-filter" id="filterBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Cek kesesuaian lahan" style="font-family: Nunito"><i id="icon-query" class="fa-solid fa-magnifying-glass-location"></i> Cek Kesesuaian Lahan</button>
        </div>
        <div id="legend">
            <h6 style="text-align: center"><strong>Keterangan</strong></h6>
            <button type="button" id="closeLegendBtn" class="btn-close" aria-label="Close" style="display: none;"></button>
            <div id="legendContent"></div>
        </div>      
    </div>  
    <div id="popup" class="ol-popup" style="display:none">
        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
        <div id="popup-content"></div>
    </div>        
    <div id="popupqueryparam" class="popupqueryparam" style="display:none">
        <a href="#" id="popupqueryparam-closer" class="popupqueryparam-closer"></a>
        <div id="popupqueryparam-content"></div>
    </div>        
    <div id="popupqueryparamatt" class="popupqueryparamatt" style="display:none">
        <a href="#" id="popupqueryparamatt-closer" class="popupqueryparamatt-closer"></a>
        <div id="popupqueryparamatt-content"></div>
    </div>        
    <div id="popup-query" class="popup-query" style="display:none; position: absolute;">
        <a href="#" id="popup-closer-query" class="popup-closer-query"></a>
        <div id="popup-content-query"></div>
    </div>      
    <div id="searchModal" class="card" style="width: 250px; height:90px;">
        <div class="card-body">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <input type="text" id="longitude" class="form-control-sm mb-1" placeholder="Masukkan Longitude" required>
                <button type="button" id="track" class="btn-track" data-bs-toggle="tooltip" data-bs-placement="top" title="Lokasi terkini" style="position: absolute; right:33px;border-radius:5px;width: 30px">
                    <i class="fa-solid fa-location-dot fa-xs"></i>
                </button>
            </div>
            <input type="text" id="latitude" class="form-control-sm" placeholder="Masukkan Latitude" required>        
            <!-- Pindahkan tombol cari di bawah kolom isian -->
            <button type="button" id="cariLokasi" class="btn btn-success" style="position: absolute; left:185px;">Cari</button>
            <button type="button" id="closeModal" class="btn-close position-absolute" style="top:5px;right:5px" aria-label="Close"></button>            
        </div>
    </div>
    <div class="diagram">
        <div id="diagram-card" class="card" style="display: none;">
            <div id="diagramContent"></div>
        </div>
    </div> 
    <div id="queryModal" class="card">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist" style="font-size:14px;">
                <button class="nav-link active" id="nav-attributes-tab" data-bs-toggle="tab" data-bs-target="#nav-attributes" type="button" role="tab" aria-controls="nav-attributes" aria-selected="true">Jenis Tanaman</button>
                <button class="nav-link" id="nav-draw-tab" data-bs-toggle="tab" data-bs-target="#nav-draw" type="button" role="tab" aria-controls="nav-draw" aria-selected="false">Area</button>
                <button class="nav-link" id="nav-attparams-tab" data-bs-toggle="tab" data-bs-target="#nav-attparams" type="button" role="tab" aria-controls="nav-draw" aria-selected="false">Parameter</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-attributes" role="tabpanel" aria-labelledby="nav-attributes-tab">
                <p style="font-size:14px;width: 100%; font-family:Nunito; text-align: justify; background-color: #fffbcc; padding: 5px; border-radius: 5px; border: 1px solid #ffd966;">Silakan pilih jenis tanaman, parameter, dan masukkan nilai yang akan dicari!</p>
                {{-- <label for="layer">Jenis Tanaman</label> --}}
                <select id="layer" class="form-select-sm mb-3" style="width: 100%; font-family:Nunito;">
                    <option value="" placeholder="Pilih Jenis Tanaman">Pilih Jenis Tanaman</option>
                    <option value="Padi">Padi</option>
                    <option value="Jagung">Jagung</option>
                    <option value="Kedelai">Kedelai</option>                    
                    <option value="Padijagung">Padi & Jagung</option>                    
                    <option value="Padikedelai">Padi & Kedelai</option>                    
                    <option value="Jagungkedelai">Jagung & Kedelai</option>                    
                    <option value="Pajale">Padi, Jagung & Kedelai</option>                    
                </select>
                <br>
                {{-- <label for="parameter">Parameter</label> --}}
                <select id="parameter" class="form-select-sm mb-3" style="width: 100%; font-family:Nunito">
                    <option value="" placeholder="Pilih Parameter">Pilih Parameter</option>
                </select>
                <br>
                <div id="input-container">
                    {{-- <label for="value">Masukkan Nilai</label> --}}
                    <input type="text" id="value"  name="value" class="form-control-sm mb-3 col-sm" style="width: 100%; font-family:Nunito" placeholder="Masukkan nilai...">
                </div>
                {{-- <div id="info-list-information" class="list-information"> --}}
                <div class="mb-3 text-end">
                    <button type="button" id="queryButton"class="btn btn-success btn-sm">Cari</button>
                    <button type="button" id="cancelqueryButton"class="btn btn-warning btn-sm">Batal</button>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-draw" role="tabpanel" aria-labelledby="nav-draw-tab">
                <p style="color:black; font-size:14px; width: 100%; font-family:Nunito; text-align: justify; background-color: #fffbcc; padding: 5px; border-radius: 5px; border: 1px solid #ffd966;">Silakan pilih kapanewon dan tekan tombol "Pilih Area" untuk menggambar area yang dicari!</p>
                {{-- <label for="layer">Jenis Tanaman</label> --}}
                <select id="layerkapanewon" class="form-select-sm mb-3"style="width: 100%; font-family:Nunito;">
                    <option value="">Pilih Kapanewon</option>
                    <option value="Moyudan">Moyudan</option>
                    <option value="Godean">Godean</option>
                    <option value="Minggir">Minggir</option>                                       
                    <option value="Gamping">Gamping</option>
                    <option value="Seyegan">Seyegan</option>
                    <option value="Sleman">Sleman</option>                                        
                    <option value="Ngaglik">Ngaglik</option>
                    <option value="Mlati">Mlati</option>
                    <option value="Tempel">Tempel</option>                                       
                    <option value="Turi">Turi</option>
                    <option value="Prambanan">Prambanan</option>
                    <option value="Kalasan">Kalasan</option>                                      
                    <option value="Berbah">Berbah</option>
                    <option value="Ngemplak">Ngemplak</option>
                    <option value="Pakem">Pakem</option>                                   
                    <option value="Depok">Depok</option>
                    <option value="Cangkringan">Cangkringan</option> 
                </select>
                <div class="mb-3 text-end">
                    <button type="button" id="drawqueryButton"class="btn btn-success btn-sm">Pilih Area</button>
                    <button type="button" id="canceldrawqueryButton"class="btn btn-warning btn-sm">Batal</button>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-attparams" style="width: 300px;" role="tabpanel" aria-labelledby="nav-draw-tab">
                <p style="color:black; font-size:14px; width: 270px; font-family:Nunito; text-align: justify; background-color: #fffbcc; padding: 5px; border-radius: 5px; border: 1px solid #ffd966;">Isi parameter yang Anda ketahui!</p>
                <div id="searchForm" class="search-form" style="width:275px">
                    <div class="row">
                        <label for="temperature" class="col-4 col-form-label" style="font-family: 'Nunito';font-size:14px;">Temperatur</label>
                        <div class="col-6">
                            <input type="number" id="temperature"  name="temperature" class="form-control-sm mb-3" style="width:160px; font-family:Nunito; font-size:14px;" placeholder="°C">
                        </div>
                    </div>
                  
                    <div class="row">
                        <label for="drainage" class="col-4 col-form-label" style="font-family: 'Nunito';font-size:14px;">Drainase</label>
                        <div class="col-6">
                            <select id="drainage" name="drainage" class="form-control-sm mb-3" style="width:160px; font-family:Nunito; font-size:14px;">
                                <option value="" selected>Pilih jenis drainase</option>                                    
                                <option value="Agak baik">Agak Baik</option>
                                <option value="terhambat">Terhambat</option>
                                <option value="agak_terhambat">Agak Terhambat</option>
                                <option value="baik">Baik</option>
                                <option value="sangat_terhambat">Sangat Terhambat</option>
                                <option value="agak_cepat">Agak Cepat</option>
                                <option value="cepat">Cepat</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <label for="texture" class="col-4 col-form-label" style="font-family: 'Nunito';font-size:14px;">Tekstur</label>
                        <div class="col-6">
                            <select id="texture" name="texture" class="form-control-sm mb-3" style="width:160px; font-family:Nunito;font-size:14px;">
                                <option value="" selected>Pilih tekstur tanah</option>
                                <option value="kasar">Kasar</option>
                                <option value="agak kasar">Agak Kasar</option>
                                <option value="sedang">Sedang</option>
                                <option value="agak halus">Agak Halus</option>
                                <option value="halus">Halus</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <label for="slope" class="col-4 col-form-label" style="font-family: 'Nunito';font-size:14px;">Kemiringan Lereng</label>
                        <div class="col-6">
                            <select id="slope" name="slope" class="form-control-sm mb-3" style="width:160px; font-family:Nunito;font-size:14px;">
                                <option value="" selected>Pilih kemiringan lereng</option>
                                <option value="datar">Datar</option>
                                <option value="agak landai">Agak Landai</option>
                                <option value="landai">Landai</option>
                                <option value="agak curam">Agak Curam</option>
                                <option value="curam">Curam</option>
                            </select>
                        </div>
                    </div>
                  
                    <div class="row">
                        <label for="erosion" class="col-4 col-form-label" style="font-family: 'Nunito';font-size:14px;">Erosi</label>
                        <div class="col-6">
                            <select id="erosion" name="erosion" class="form-control-sm mb-3" style="width:160px; font-family:Nunito;font-size:14px;">
                                <option value="" selected>Tingkat bahaya erosi</option>
                                <option value="sangat ringan">Sangat Ringan</option>
                                <option value="ringan">Ringan</option>
                                <option value="sedang">Sedang</option>
                                <option value="berat">Berat</option>
                                <option value="sangat berat">Sangat Berat</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <label for="flood" class="col-4 col-form-label" style="font-family: 'Nunito';font-size:14px;">Banjir</label>
                        <div class="col-6">
                            <select id="flood" name="flood" class="form-control-sm mb-3" style="width:160px; font-family:Nunito;font-size:14px;">
                                <option value="" selected>Tingkat bahaya banjir</option>
                                <option value="tidak ada">Tidak Ada</option>
                                <option value="sangat ringan">Sangat Ringan</option>
                                <option value="ringan">Ringan</option>
                                <option value="sedang">Sedang</option>
                                <option value="agak berat">Agak Berat</option>
                                <option value="berat">Berat</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3 float-end" style="margin-right:40px">
                    <button type="button" id="query-attparamsButton"class="btn btn-success btn-sm">Cari</button>
                    <button type="button" id="cancelquery-attparamsButton"class="btn btn-warning btn-sm">Batal</button>
                </div>
            </div>
        </div> 
        
    </div>
    <!-- Toast Notification -->
    <div id="toast" class="toast-notification"><strong>Tidak ada lahan dengan kelas "Sangat Sesuai" pada lokasi ini.</strong></div>
</main>
@endsection

@section('scripts')
<script type="module" src="{{ asset('/build/assets/map-DqIWeMN7.js') }}"></script>
<script type="module" src="{{ asset('/build/assets/mappage-l0sNRNKZ.js') }}"></script>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection