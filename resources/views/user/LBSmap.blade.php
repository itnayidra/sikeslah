@extends('layout.mainapplogin')

@section('head')
<link rel="stylesheet" href="{{ asset('/build/assets/mapLBS-D19YYr5S.css') }}">
<link rel="stylesheet" href="{{ asset('/build/assets/mappage-CqyiwhDG.css') }}">
    {{-- @vite(['resources/css/mapLBS.css', 'resources/js/mapLBS.js','resources/css/mappage.css', 'resources/js/mappage.js',]) --}}
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
        <div class="diagram-button">
            <button type="button" id="diagram" class="btn-diagram" data-bs-toggle="tooltip" data-bs-placement="top" title="Tampilkan diagram"><i class="fas fa-chart-pie"></i></button>                
        </div>
        <div class="unduhGeoJSON-button">
            <button type="button" id="unduhGeoJSON" class="btn-unduhGeoJSON" data-bs-toggle="tooltip" data-bs-placement="top" title="Unduh data"><i class="fa-solid fa-download"></i></button>                
        </div>
        <div id="legend">
            <h6 style="text-align: center"><strong>Keterangan</strong></h6>
            {{-- <button type="button" id="showLegendBtn" class="btn btn-light">Tampilkan Legenda</button> --}}
            <button type="button" id="closeLegendBtn" class="btn-close" aria-label="Close" style="display: none;"></button>
            <div id="legendContent"></div>
        </div>           
    </div>  
    <div id="popup" class="ol-popup" style="display:none">
        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
        <div id="popup-content"></div>
    </div>
    <div class="diagram">
        <div id="diagram-card" class="card" style="display: none;">
            <div id="diagramContent"></div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script type="module" src="{{ asset('/build/assets/mapLBS-n-OPb_R-.js') }}"></script>
<script type="module" src="{{ asset('/build/assets/mappage-l0sNRNKZ.js') }}"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection