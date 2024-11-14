import 'ol/ol.css';
import Map from 'ol/Map.js';
import OSM from 'ol/source/OSM.js';
import XYZ from 'ol/source/XYZ.js';
import TileLayer from 'ol/layer/Tile.js';
import Group from 'ol/layer/Group.js';
import View from 'ol/View.js';
import MousePosition from 'ol/control/MousePosition.js';
import Draw from 'ol/interaction/Draw.js';
import VectorLayer from 'ol/layer/Vector.js';
import VectorSource from 'ol/source/Vector.js'; 
import {Circle, Fill, Stroke, Style} from 'ol/style.js';
import { GeoJSON, WKT, WMSCapabilities } from "ol/format";
import {getArea, getLength} from 'ol/sphere.js';
import { Polygon} from 'ol/geom.js';
import LayerSwitcher from 'ol-layerswitcher';
import ScaleLine from 'ol/control/ScaleLine.js';

var view = new View({
    projection: 'EPSG:4326',
    center: [110.38399799809093, -7.7043285625487075],
    zoom: 11.5,
});

var map = new Map({
    target: 'map',
    view: view,
    layers: [
        new TileLayer({
            source: new XYZ({
                url: 'http://server.arcgisonline.com/ArcGIS/rest/services/' + 'World_Imagery/MapServer/tile/{z}/{y}/{x}'
            })
        }),
        new TileLayer({
            source: new XYZ({
                url: 'https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}'
            }),
        }),
    ],
    controls: []
    // overlays: [overlay]
});

var base_maps = new Group({
    'title': 'Peta Dasar',
    layers: [
        new TileLayer({
            title: 'Open Street Map',
            type: 'base',
            source: new OSM()
        }),
        new TileLayer({
            title: 'Satellite Map',
            type: 'base',
            source: new XYZ({
                url: 'http://server.arcgisonline.com/ArcGIS/rest/services/' +
                    'World_Imagery/MapServer/tile/{z}/{y}/{x}'
                })
            }),
            new TileLayer({
                source: new XYZ({
                url: 'https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}'
            })
        }),	
    ],
});

map.addLayer(base_maps);

var mouse_position = new MousePosition();
map.addControl(mouse_position);


/** Zoom In */ 
document.getElementById('zoomin').addEventListener('click', function () {
    const view = map.getView();
    const zoom = view.getZoom();
    view.setZoom(zoom + 1);
});

/** Zoom Out */ 
document.getElementById('zoomout').addEventListener('click', function () {
    const view = map.getView();
    const zoom = view.getZoom();
    view.setZoom(zoom - 1);
});
// Layer Switcher
var layerSwitcher = new LayerSwitcher({
    activationMode: 'click',
    tipLabel: 'Tampilkan peta', // Optional label for button
    collapseTipLabel: 'Sembunyikan peta', // Optional label for button
    groupSelectStyle: 'children' // Can be 'children' [default], 'group' or 'none'
});
map.addControl(layerSwitcher);

// SKALA
var scale_line = new ScaleLine({
    units: 'metric',
    bar: true,
    steps: 6,
    text: true,
    minWidth: 140,
    target: 'scale_bar'
});
map.addControl(scale_line);

// var draw = new Draw({
//     source: source,
//     type: 'Polygon'
// });
// map.addInteraction(draw);

//DRAW
var source = new VectorSource();
var vector = new VectorLayer({
    source: source,
    style: new Style({
        fill: new Fill({
            color: 'rgba(255, 255, 255, 0.2)'
        }),
        stroke: new Stroke({
            color: '#ffcc33',
            width: 2
        }),
        image: new Circle({
            radius: 7,
            fill: new Fill({
                color: '#ffcc33'
            }),
        }),
    }),
});
map.addLayer(vector);

var draw;
draw = new Draw({
    source: source,
    type: 'Polygon'
});
map.addInteraction(draw);

// Event ketika gambar selesai
draw.on('drawend', function(e) {
    var feature = e.feature;
    
    var geometry = feature.getGeometry();
    console.log(geometry);

    source.clear();
    // source.addFeature(feature);
    // source.addFeature(e.feature);

    if (geometry.getType() === 'Polygon') {
        var area = getArea(geometry,{projection: 'EPSG:4326'}); // Hitung luas dari geometri
        let output;

        output = ((area * 100)/100).toFixed(2) + ' m<sup>2</sup>'; 
        // if (area > 10000) {
        //     output = Math.round((area / 10000) * 100) / 100 + ' ha'; // Format km²
        // } else {
        //     output = Math.round(area * 100) / 100
        
        // + ' m<sup>2</sup>'; // Format m²
        // }
        console.log('Luas:', output);
        document.getElementById('luas').value = area; // Simpan nilai numerik di input luas
        // Menampilkan hasil pengukuran di dalam form
        document.getElementById('luas').innerHTML = output; 

        var format = new WKT();
        var drawnItemWKT = format.writeGeometry(feature.getGeometry());
        document.getElementById('area').value = drawnItemWKT;
    }
});

// var lastDrawnPolygon = null;



// // Ambil ID dari URL
// const urlParts = window.location.pathname.split('/');
// const polygonId = urlParts[urlParts.length - 2]; // ID Polygon berada di posisi sebelum 'edit'

// // console.log('Polygon ID:', polygonId);

// /// Variabel untuk menyimpan referensi vectorLayer
// let vectorLayer = null;

// // Fungsi untuk mengambil data GeoJSON berdasarkan ID Polygon
// function fetchPolygonData(polygonId) {
//     fetch('/api/get-polygon/' + polygonId)  // Ganti dengan endpoint API yang sesuai
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Network response was not ok');
//             }
//             return response.json();
//         })
//         .then(data => {
//             console.log('Data GeoJSON:', data);  // Untuk debug, pastikan data diterima
//             if (data) {
//                 // Menghapus poligon lama jika ada
//                 if (lastDrawnPolygon) {
//                     source.removeFeature(lastDrawnPolygon);  // Hapus poligon sebelumnya
//                 }

//                 // Menghapus vectorLayer yang sudah ada sebelumnya jika ada
//                 if (vectorLayer) {
//                     map.removeLayer(vectorLayer);  // Hapus layer sebelumnya
//                 }

//                 // Menambahkan data GeoJSON baru ke peta
//                 addGeoJSONToMap(data);
//             }
//         })
//         .catch(error => {
//             console.error('Error fetching GeoJSON:', error);
//         });
// }

// // Fungsi untuk menambahkan GeoJSON ke peta
// function addGeoJSONToMap(geojson) {
//     const format = new GeoJSON();
//     const features = format.readFeatures(geojson, {
//         dataProjection: 'EPSG:4326',
//         featureProjection: 'EPSG:4326', // Sesuaikan proyeksi jika perlu
//     });

//     console.log('Features:', features); // Cek apakah geometri sudah terbaca dengan benar

//     const vectorSource = new VectorSource({
//         features: features,
//     });

//     // Membuat layer baru untuk poligon
//     vectorLayer = new VectorLayer({
//         source: vectorSource,
//         style: new Style({
//             fill: new Fill({
//                 color: 'rgba(255, 255, 255, 0.2)',
//             }),
//             stroke: new Stroke({
//                 color: '#ffcc33',
//                 width: 2,
//             }),
//             image: new Circle({
//                 radius: 7,
//                 fill: new Fill({
//                     color: '#ffcc33',
//                 }),
//             }),
//         }),
//     });

//     map.addLayer(vectorLayer);  // Menambahkan vectorLayer ke peta

//     // Menyesuaikan tampilan peta untuk mencakup fitur GeoJSON
//     const extent = vectorSource.getExtent();
//     console.log('Extent:', extent); // Cek extent untuk memastikan area yang ditampilkan benar
//     map.getView().fit(extent, { padding: [50, 50, 50, 50] });
// }

// // Memanggil fungsi untuk mengambil data berdasarkan ID Polygon
// fetchPolygonData(polygonId);

// draw.on('drawend', function(e) {
//     var feature = e.feature;
//     var geometry = feature.getGeometry();

//     source.clear();

//     // Periksa koordinat poligon yang baru digambar
//     // console.log('Coordinates:', geometry.getCoordinates());

//     // Menghapus poligon lama jika ada
//     if (lastDrawnPolygon) {
//         source.removeFeature(lastDrawnPolygon);  // Hapus poligon sebelumnya
//     }

//     // Menghapus vectorLayer yang sudah ada sebelumnya jika ada
//     if (vectorLayer) {
//         map.removeLayer(vectorLayer);  // Hapus layer sebelumnya
//     }
    
//     if (geometry.getType() === 'Polygon') {
//         var area = getArea(geometry, {projection: 'EPSG:4326'}); 
//         let output = (area * 100 / 100).toFixed(2); 
//         document.getElementById('luas').value = area;
//         document.getElementById('luas').innerHTML = output;

//         var format = new WKT();
//         var drawnItemWKT = format.writeGeometry(feature.getGeometry());
//         document.getElementById('area').value = drawnItemWKT;

//         // Cek extent untuk fitur yang baru digambar
//         const extent = geometry.getExtent();
//         console.log('Drawn Geometry Extent:', extent);
//     }

// });