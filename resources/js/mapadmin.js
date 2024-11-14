import 'ol/ol.css';
// import 'ol-layerswitcher/dist/ol-layerswitcher.css';
// import 'ol-popup/dist/ol-popup.css';
import Map from 'ol/Map.js';
import OSM from 'ol/source/OSM.js';
import XYZ from 'ol/source/XYZ.js';
import TileLayer from 'ol/layer/Tile.js';
import Group from 'ol/layer/Group.js';
import View from 'ol/View.js';
import MousePosition from 'ol/control/MousePosition.js';
import ScaleLine from 'ol/control/ScaleLine.js';
import Control from 'ol/control/Control.js';
import { fromLonLat } from 'ol/proj.js';
import LayerSwitcher from 'ol-layerswitcher';
import Geocoder from 'ol-geocoder/dist/ol-geocoder.js';
import { format } from 'ol/coordinate.js';
import { METERS_PER_UNIT } from 'ol/proj/Units.js';
import DragPan from 'ol/interaction/DragPan.js';
import GeoJSON from 'ol/format/GeoJSON.js';
import KML from 'ol/format/KML.js';
import Draw from 'ol/interaction/Draw.js';
import { Polygon, Point } from 'ol/geom';
import WKT from 'ol/format/WKT.js';
import { Circle as CircleStyle, Fill, Stroke, Style, Icon, RegularShape, Text } from 'ol/style.js';
import { Vector as VectorSource, Cluster } from 'ol/source.js';
import { Vector as VectorLayer } from 'ol/layer.js';
import Feature from 'ol/Feature.js';
import { getArea } from 'ol/sphere.js';
import { Image } from 'ol/layer.js';
import { ImageWMS, Source, TileWMS } from 'ol/source';
import SearchNominatim from 'ol-ext/control/SearchNominatim.js';
import Comparison from 'ol/format/filter/Comparison.js';
import Popup from 'ol-popup';
import { getCenter } from 'ol/extent';
import Overlay from 'ol/Overlay.js';
import { unByKey } from 'ol/Observable';
import { toStringHDMS } from 'ol/coordinate.js';
import { transform } from 'ol/proj.js';
import Select from 'ol/interaction/Select.js';
import { intersects, createEmpty, extend } from 'ol/extent';
import BaseLayer from 'ol/layer/Base';
import { toLonLat } from 'ol/proj.js';
import Geolocation from 'ol/Geolocation.js';
import {click} from 'ol/events/condition.js';

/** View kab sleman*/
var view = new View({
    projection: 'EPSG:4326',
    center: [110.38399799809093, -7.7043285625487075],
    zoom: 11.5,
});

/** Inisialisasi peta dan layer dasar */ 
const map = new Map({
    target: 'map',
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
    controls: [],
    view: view,
});

/** Basemap */
var base_maps = new Group({
    'title': 'Peta Dasar',
    layers: [
        new TileLayer({
            title: 'Satellite Map',
            type: 'base',				
            source: new XYZ({
                url: 'http://server.arcgisonline.com/ArcGIS/rest/services/' +
                    'World_Imagery/MapServer/tile/{z}/{y}/{x}',
                url: 'https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}'
            })
        }),	
        new TileLayer({
            title: 'Open Street Map',
            type: 'base',
            source: new OSM()
        }),
    ],
});
map.addLayer(base_maps);

/** Layer di Layer Switcher */
var overlays = new Group({
    'title': 'Layer',
    layers: []
});
map.addLayer(overlays);

var overlay_keslah = new Group({
    'title': 'Kesesuaian Lahan',
    layers: []
})
overlays.getLayers().push(overlay_keslah);

var overlay_admin = new Group({
    'title': 'Batas Administrasi',
    layers: []
})
overlays.getLayers().push(overlay_admin);

/** Layer Switcher */ 
var layerSwitcher = new LayerSwitcher({
    activationMode: 'click',
    tipLabel: 'Tampilkan peta', // Optional label for button
    collapseTipLabel: 'Sembunyikan peta', // Optional label for button
    groupSelectStyle: 'children' // Can be 'children' [default], 'group' or 'none'
});
map.addControl(layerSwitcher);

/** Style Batas Admin */ 
const styleAdmin = function (feature) {
    return new Style({
        stroke: new Stroke({
            color: 'black',
            width: 1,
        }),
        zIndex: 1000,
        text: new Text({
            text: feature.get('NAMOBJ'), // Teks yang akan ditampilkan pada fitur
            font: 'bold 10px Nunito', // Gaya font
            fill: new Fill({
                color: '#000' // Warna teks
            }),
            stroke: new Stroke({
                color: '#fff', // Warna stroke teks
                width: 2 // Lebar stroke teks
            }),
            textAlign: 'center' // Penataan teks di tengah
        })
    });
};

/** Sumber vektor untuk data GeoJSON kapanewon */ 
const kapanewonSource = new VectorSource({
    url: '/geojson/adminkap.geojson', // Ganti dengan path ke file GeoJSON Anda
    format: new GeoJSON(),
});
/** Layer vektor untuk kapanewon */
const adminkec = new VectorLayer({
    source: kapanewonSource,
    title: 'Batas Kapanewon',
    style: styleAdmin,
});
// map.addLayer(adminkec);
overlay_admin.getLayers().push(adminkec);

/** Style Keterangan Keslah */
const styleFunctionKeslah = function (feature) {
    const keterangan = feature.get('keterangan');
    let fillColor;
    let stroke;

    switch (keterangan) {
        case 'Sangat Sesuai':
            fillColor = 'rgba(87, 169, 6, 0.5)';
            stroke = 'rgba(87, 169, 6, 0.0)';
            // stroke = '#57A906';
            break;
        case 'Cukup Sesuai':
            fillColor = 'rgba(34, 238, 8, 0.5)';
            stroke = 'rgba(34, 238, 8, 0.0)';
            // stroke = '#22EE08';
            break;
        case 'Sesuai Marginal':
            fillColor = 'rgba(255, 255, 0, 0.5)';
            stroke = 'rgba(255, 255, 0, 0.0)';
            // stroke = '#FFFF00';
            break;
        case 'Tidak Sesuai':
            fillColor = 'rgba(255, 0, 0, 0.5)';
            stroke = 'rgba(255, 0, 0, 0.0)';
            // stroke = '#FF0000';
            break;
        default:
            fillColor = 'rgba(0,0,0,0)'; // Default color if 'Keterangan' doesn't match any case
            stroke = 'rgba(255, 255, 255, 0.0)'; // Default color if 'Keterangan' doesn't match any case
            // stroke = 'white'; // Default color if 'Keterangan' doesn't match any case
            // stroke = 'white'; // Default color if 'Keterangan' doesn't match any case
            break;
    }

    return new Style({
        fill: new Fill({
            color: fillColor,
        }),
        stroke: new Stroke({
            color: stroke,
            width: 1,
        }),
        zIndex: 1000,
    });
};

/** Sumber vektor untuk data GeoJSON Keslah Padi, Jagung, Kedelai */ 
const keslahpadiSource = new VectorSource({
    url: '/geojson/padi.geojson',
    format: new GeoJSON(),
});
// Layer vektor untuk Keslah Padi
const keslahpadi = new VectorLayer({
    source: keslahpadiSource,
    title: 'Padi',
    style: styleFunctionKeslah,
    visible:true,
});

const keslahjagungSource = new VectorSource({
    url: '/geojson/jagung.geojson', // Ganti dengan path ke file GeoJSON Anda
    format: new GeoJSON(),
});
// Layer vektor untuk kapanewon
const keslahjagung = new VectorLayer({
    source: keslahjagungSource,
    title: 'Jagung',
    style: styleFunctionKeslah,
    visible:false,
});

const keslahkedelaiSource = new VectorSource({
    url: '/geojson/kedelai.geojson', // Ganti dengan path ke file GeoJSON Anda
    format: new GeoJSON(),
});
// Layer vektor untuk kapanewon
const keslahkedelai = new VectorLayer({
    source: keslahkedelaiSource,
    title: 'Kedelai',
    style: styleFunctionKeslah,
    visible:false,
});
// map.addLayer(keslahPadiLayer);
overlay_keslah.getLayers().push(keslahkedelai);
overlay_keslah.getLayers().push(keslahjagung);
overlay_keslah.getLayers().push(keslahpadi);

// // MOUSE POINTER COORDINATE
// var mouse_position = new MousePosition();
// map.addControl(mouse_position);

/** Geolocation */ 
const geolocation = new Geolocation({
    // enableHighAccuracy must be set to true to have the heading value.
    trackingOptions: {
        enableHighAccuracy: true,
    },
    projection: view.getProjection(),
});

// Function to get element by ID
function el(id) {
    return document.getElementById(id);
}

// Track the current state of geolocation tracking
let tracking = false;

// Event listener to toggle tracking on button click
el('track').addEventListener('click', function () {
    tracking = !tracking;
    geolocation.setTracking(tracking);

    // Add the geolocation layer when tracking is enabled
    if (tracking) {
        if (!map.getLayers().getArray().includes(point_geolocation)) {
            map.addLayer(point_geolocation);
        }
    } else {
        map.removeLayer(point_geolocation);
    }
});

// Handle geolocation errors
geolocation.on('error', function (error) {
    const info = document.getElementById('info');
    info.innerHTML = error.message;
    info.style.display = '';
});

// Create features for accuracy and position
const accuracyFeature = new Feature();
geolocation.on('change:accuracyGeometry', function () {
    accuracyFeature.setGeometry(geolocation.getAccuracyGeometry());
});

const positionFeature = new Feature();
positionFeature.setStyle(
    new Style({
        image: new CircleStyle({
            radius: 6,
            fill: new Fill({
                color: 'black',
            }),
            stroke: new Stroke({
                color: '#fff',
                width: 2,
            }),
        }),
    })
);

geolocation.on('change:position', function () {
    const coordinates = geolocation.getPosition();
    positionFeature.setGeometry(coordinates ? new Point(coordinates) : null);
    // Center the map on the user's location and set the zoom level
    if (coordinates) {
        view.setCenter(coordinates);
        view.setZoom(12); // Adjust the zoom level as needed

        // Masukkan nilai ke dalam input longitude dan latitude
        el('longitude').value = coordinates[0].toFixed(6);; // Longitude
        el('latitude').value = coordinates[1].toFixed(6);;  // Latitude
    }
});

// Initialize map layers
var point_geolocation = new VectorLayer({
    source: new VectorSource({
        features: [accuracyFeature, positionFeature],
    }),
});
map.addLayer(point_geolocation);

// Fungsi untuk mengatur ulang peta ke posisi awal
function resetMap() {
    map.getView().animate({
        center: [110.38399799809093, -7.7043285625487075],
        zoom: 11.5,
        duration: 1000 // Durasi animasi dalam milidetik
    });
}

/** Pusatkan Peta */ 
document.getElementById('base').addEventListener('click', resetMap);

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

/** Skala */ 
var scale_line = new ScaleLine({
    units: 'metric',
    bar: true,
    steps: 6,
    text: true,
    minWidth: 140,
    target: 'scale_bar'
});
map.addControl(scale_line);

/** Legenda */
let currentIcons = {}; // Menyimpan ikon yang digunakan untuk hasil pencarian
let currentIconsQ3 = {};
var isSearchActive = false; // Status apakah pencarian aktif atau tidak

// Fungsi untuk memperbarui legenda
function updateLegend() {
    let content = []; // Menyimpan konten legenda

    if (isSearchActive) {
        // Jika pencarian aktif, tambahkan ikon dari 'currentIcons' ke 'content'
        Object.keys(currentIcons).forEach(key => {
            if (currentIcons[key]) { // Memeriksa apakah ikon ada
                content.push({ label: key, iconUrl: currentIcons[key] });
            }
        });
         // Iterasi kedua untuk menambahkan ikon berdasarkan jenis tanaman dan keterangan
        Object.keys(currentIconsQ3).forEach(plantType => {
            if (currentIconsQ3[plantType]) { // Memeriksa apakah ada keterangan untuk plantType
                Object.keys(currentIconsQ3[plantType]).forEach(ket => {
                    const iconUrl = currentIconsQ3[plantType][ket];
                    content.push({ label: `${plantType} - ${ket}`, iconUrl: iconUrl });
                });
            }
        });
    } else {
        // Fungsi untuk memperbarui legenda berdasarkan layer dari grup tertentu
        function updateLegendForGroup(group) {
            group.getLayers().forEach(layer => {
                if (layer.getVisible() && layer.get('title')) {
                    let legendUrl = '';
                    // Tentukan URL legenda berdasarkan judul layer
                    if (layer.get('title') === 'Batas Administrasi') {
                        legendUrl = '/icon/legend_admin.png'; // Ganti dengan URL legenda Layer A
                    } else if (layer.get('title') === 'Kesesuaian Lahan') {
                        legendUrl = '/icon/keslah.png'; // Ganti dengan URL legenda Layer C
                    } 
                    // Tambahkan ke konten jika ada URL
                    if (legendUrl) {
                        content.push({ label: layer.get('title'), iconUrl: legendUrl });
                    }
                }
            });
        }
        // Perbarui konten legenda untuk masing-masing grup
        updateLegendForGroup(overlays);
    }
    // Memperbarui konten legenda dengan data yang telah dikumpulkan
    // console.log('Content untuk legenda:', content); 
    updateLegendContent(content);
}

// Fungsi untuk memperbarui konten legenda
function updateLegendContent(content) {
    const legendContent = document.getElementById('legendContent');
    legendContent.innerHTML = ''; // Hapus konten legenda sebelumnya

    if (content.length === 0) {
        legendContent.innerHTML = '<p>Tidak ada data untuk ditampilkan</p>'; // Tampilkan pesan jika tidak ada data
        return;
    }
    
    content.forEach(({ label, iconUrl }) => {
        const labelDiv = document.createElement('div');
        labelDiv.innerHTML = `<strong>${label}</strong>`; // Label untuk ikon
        legendContent.appendChild(labelDiv);

        const img = document.createElement('img');
        img.src = iconUrl;
        // // img.style.width = '100%'; // Mengatur lebar gambar agar seragam
        // img.style.height = '100%'; // Mengatur tinggi gambar agar seragam
        legendContent.appendChild(img);
    });
}


// Fungsi untuk menampilkan hasil pencarian di legenda dan sembunyikan layer lainnya
function showSearchIcons() {
    isSearchActive = true; // Set status pencarian aktif
    updateLegend(); // Perbarui legenda dengan ikon hasil pencarian saja
}

// Fungsi untuk menghapus ikon hasil pencarian dari legenda dan tampilkan kembali layer lainnya
function clearSearchIcons() {
    isSearchActive = false; // Set status pencarian tidak aktif
    updateLegend(); // Perbarui legenda tanpa ikon hasil pencarian, hanya layer-layer aktif
}

// Event listener untuk memantau perubahan visibilitas layer
function monitorLayerVisibility(group) {
    group.getLayers().forEach(layer => {
        layer.on('change:visible', updateLegend); // Perbarui legenda setiap kali visibilitas layer berubah
    });
}

// Panggil updateLegend untuk pertama kali saat halaman dimuat
updateLegend();

// Monitor perubahan visibilitas layer
monitorLayerVisibility(overlays);

/** Kursor */
var kursorButton = document.getElementById('cursor');
kursorButton.addEventListener('click', disableInfoPopup);
kursorButton.addEventListener('click', () => {
    // Highlight info
    if (selectedFeature) {
        clearHighlight();
    }
    // Nonaktifkan interaksi pengukuran jika aktif
    if (drawMeasure) {
        map.removeInteraction(drawMeasure);
        drawMeasure = null; // Reset variabel drawMeasure
         // Hapus hasil pengukuran
         sourceMeasurement.clear(); // Menghapus fitur yang digambar
    }
    // Hapus measureTooltip jika ada
    if (measureTooltip) {
        map.removeOverlay(measureTooltip); // Menghapus overlay dari peta
        measureTooltipElement = null; // Reset variabel measureTooltipElement
        measureTooltip = null; // Reset variabel measureTooltip
    }
    // Hapus semua tooltip statis (ol-tooltip-static)
    const staticTooltips = document.querySelectorAll('.ol-tooltip-static');
    staticTooltips.forEach((tooltip) => {
        tooltip.remove(); // Menghapus elemen tooltip dari DOM
    });
    // Optional: Hapus helpTooltip jika ada
    if (helpTooltip) {
        map.removeOverlay(helpTooltip);
        helpTooltipElement = null;
        helpTooltip = null;
    }
    // Search Location
    document.getElementById('searchModal').style.display = 'none'; // Menyembunyikan modal
    map.removeLayer(point_geolocation);
    popup.getElement().style.display = 'none';
    // Search land suit
    map.removeLayer(previousLayer);
    map.removeLayer(previousPointGeolocation);
    map.removeLayer(previousMarkerLayerJagung);
    map.removeLayer(previousMarkerLayerKedelai);
    map.removeLayer(previousMarkerLayerPadi);
    map.removeLayer(vectorLayerQuery1);
    map.removeInteraction(selectPolygonQuery1);
    overlayInfoQuery.setPosition(undefined);
    // Search land suit q3
    overlayInfoQuery3.setPosition(undefined);
    currentIcons = {};
    currentIconsQ3 = {};
    // Search land suit q2
    vectorKapLayer.setVisible(false);
    
    clearSearchIcons();
    drawnQuerySource.clear();

    updateLegend();

    document.getElementById('queryModal').style.display = 'none';
});

/** Info */
// Variable to track the active state of the info popup
let isInfoPopupActive = false;

// Function to enable info popup
function enableInfoPopup() {
    isInfoPopupActive = true;
}

// Function to disable info popup
function disableInfoPopup() {
    isInfoPopupActive = false;
    popup.setPosition(undefined); // Remove the popup if it's deactivated
}

// Button event listeners
var infoButton = document.getElementById('info');
infoButton.addEventListener('click', enableInfoPopup);
infoButton.addEventListener('click', () => {
    // Nonaktifkan interaksi pengukuran jika aktif
    if (drawMeasure) {
        map.removeInteraction(drawMeasure);
        drawMeasure = null; // Reset variabel drawMeasure
         // Hapus hasil pengukuran
         sourceMeasurement.clear(); // Menghapus fitur yang digambar
    }
    // Hapus measureTooltip jika ada
    if (measureTooltip) {
        map.removeOverlay(measureTooltip); // Menghapus overlay dari peta
        measureTooltipElement = null; // Reset variabel measureTooltipElement
        measureTooltip = null; // Reset variabel measureTooltip
    }
    // Hapus semua tooltip statis (ol-tooltip-static)
    const staticTooltips = document.querySelectorAll('.ol-tooltip-static');
    staticTooltips.forEach((tooltip) => {
        tooltip.remove(); // Menghapus elemen tooltip dari DOM
    });
    // Optional: Hapus helpTooltip jika ada
    if (helpTooltip) {
        map.removeOverlay(helpTooltip);
        helpTooltipElement = null;
        helpTooltip = null;
    }
    // Search Location
    document.getElementById('searchModal').style.display = 'none'; // Menyembunyikan modal
    map.removeLayer(point_geolocation);
    popup.getElement().style.display = 'none';

    clearSearchResults();
    document.getElementById('queryModal').style.display = 'none';
    if(hideAllLayers){
        showAllLayers();
    };
})

// var drawqueryButton = document.getElementById('drawqueryButton');
// drawqueryButton.addEventListener('click', disableInfoPopup);

// Inisialisasi sumber data polygon (dari PostGIS, GeoJSON, atau WFS)
var vectorInfoPopupSource = new VectorSource({
    format: new GeoJSON(),
    url: '/geojson/sawahkeslah.geojson', // Sesuaikan dengan URL atau data GeoJSON Anda
});

// Inisialisasi layer untuk polygon
var vectorInfoPopupLayer = new VectorLayer({
    source: vectorInfoPopupSource,
    style: new Style({
        // stroke: new Stroke({
        //     color: 'blue',
        //     width: 2
        // }),
        fill: new Fill({
            color: 'rgba(0, 0, 0, 0)'
        })
    })
});
// Tambahkan layer polygon ke map
map.addLayer(vectorInfoPopupLayer);
// Fungsi untuk menampilkan popup
function showInfoPopup(coordinate, data) {
    var container = document.getElementById('popup');
    var content = document.getElementById('popup-content');
    var overlay = new Overlay({
        element: container,
        autoPan: true,
        autoPanAnimation: { duration: 250 }
    });

    // Menambahkan overlay ke map
    map.addOverlay(overlay);

    // Isi konten popup
    if (data) {
        const luas = parseFloat(data.get('luas_pl')).toFixed(2);
        const formatluas = Number(parseFloat(luas).toFixed(2)).toLocaleString('id-ID');
        content.innerHTML = `
             <strong style="font-family: 'Nunito'">Informasi Lahan</strong>
                <table style="width:100%; border-collapse: collapse; margin-top: 2px;">
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">Kapanewon</td>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">${data.get('kapanewon')}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">Jenis Lahan</td>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">${data.get('pl')}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">Luas</td>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">${formatluas} m<sup>2</sup></td>
                    </tr>
                </table>
                
                <strong style="font-family: 'Nunito';margin-top: 7px;">Kesesuaian Lahan</strong>
                <table style="width:100%; border-collapse: collapse; margin-top: 2px;">
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">Padi</td>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">${data.get('ket_padi')}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">Jagung</td>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">${data.get('ket_jag')}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">Kedelai</td>
                        <td style="border: 1px solid #ddd; padding: 8px; font-family: 'Nunito'">${data.get('ket_ked')}</td>
                    </tr>
                </table>
        `;
    } else {
        content.innerHTML = '<p>Data tidak ditemukan</p>';
    }

    // Tampilkan popup di lokasi yang diklik
    overlay.setPosition(coordinate);
    container.style.display = 'block';

    // Event untuk menutup popup
    document.getElementById('popup-closer').onclick = function () {
        overlay.setPosition(undefined);
        // Hilangkan highlight ketika popup ditutup
        clearHighlight();
        return false;
    };
}

// Variabel untuk menyimpan fitur yang terpilih sebelumnya
let selectedFeature = null;

// Style untuk fitur yang dihighlight
const highlightStyle = new Style({
    stroke: new Stroke({
        color: '#ffcc33', // Warna outline kuning
        width: 3
    }),
    fill: new Fill({
        color: 'rgba(255, 204, 51, 0.3)' // Warna isian dengan transparansi
    })
});

// Fungsi untuk menghapus highlight dari fitur yang terpilih
function clearHighlight() {
    if (selectedFeature) {
        selectedFeature.setStyle(null); // Kembalikan ke style default
        selectedFeature = null; // Reset fitur yang terpilih
    }
}
// Event saat peta diklik
map.on('singleclick', function (evt) {
    if (!isInfoPopupActive) {
        return; // Hentikan jika tidak dalam mode Info
    }

    var coordinate = evt.coordinate;

    // Dapatkan fitur dari layer yang diklik
    map.forEachFeatureAtPixel(evt.pixel, function (feature) {
        // Jika ada fitur yang terpilih sebelumnya, reset stylenya
        if (selectedFeature) {
            clearHighlight();
        }

        // Simpan fitur yang baru dipilih
        selectedFeature = feature;

        // Terapkan style highlight ke fitur yang dipilih
        selectedFeature.setStyle(highlightStyle);

        // Tampilkan popup jika fitur polygon ditemukan
        showInfoPopup(coordinate, feature);
    }, {
        layerFilter: function (layer) {
            return layer === vectorInfoPopupLayer; // Hanya layer polygon yang diproses
        }
    });

    // Atur peta agar fokus ke koordinat yang dipilih
    map.getView().setCenter(coordinate);
    map.getView().setZoom(17); 
});

/** Query by Jenis Tanaman */ 
// Membuka modal
document.getElementById('filterBtn').onclick = function () {
    document.getElementById('queryModal').style.display = 'block';
    hideAllLayers(); // Hide semua layer
    legendContent.innerHTML = '';
    popup.getElement().style.display = 'none';
    clearHighlight();
    if (drawMeasure) {
        map.removeInteraction(drawMeasure);
        drawMeasure = null; // Reset variabel drawMeasure
         // Hapus hasil pengukuran
         sourceMeasurement.clear(); // Menghapus fitur yang digambar
    }
    // Hapus measureTooltip jika ada
    if (measureTooltip) {
        map.removeOverlay(measureTooltip); // Menghapus overlay dari peta
        measureTooltipElement = null; // Reset variabel measureTooltipElement
        measureTooltip = null; // Reset variabel measureTooltip
    }
    // Hapus semua tooltip statis (ol-tooltip-static)
    const staticTooltips = document.querySelectorAll('.ol-tooltip-static');
    staticTooltips.forEach((tooltip) => {
        tooltip.remove(); // Menghapus elemen tooltip dari DOM
    });
    // Optional: Hapus helpTooltip jika ada
    if (helpTooltip) {
        map.removeOverlay(helpTooltip);
        helpTooltipElement = null;
        helpTooltip = null;
    }
    // Search Location
    document.getElementById('searchModal').style.display = 'none'; // Menyembunyikan modal
    map.removeLayer(point_geolocation);
    popup.getElement().style.display = 'none';
};

// Function untuk uncheck seluruh layer
function hideAllLayers() {
    map.getLayers().forEach(function (layer) {
        if (layer instanceof Group) {
            console.log('Group Layer:', layer.get('title'), 'Sub-layers:', layer.getLayers().getArray());
            layer.getLayers().forEach(function (subLayer) {
                if (subLayer instanceof BaseLayer) {
                    console.log('Sub Layer in Group:', subLayer.get('title')); // Debug untuk setiap sub-layer
                    subLayer.setVisible(false); // Sembunyikan layer
                }
            });
        }
    });
}

function showAllLayers() {
    map.getLayers().forEach(function (layer) {
        if (layer instanceof Group) {
            console.log('Group Layer:', layer.get('title'), 'Sub-layers:', layer.getLayers().getArray());
            layer.getLayers().forEach(function (subLayer) {
                if (subLayer instanceof BaseLayer) {
                    console.log('Sub Layer in Group:', subLayer.get('title')); // Debug untuk setiap sub-layer
                    subLayer.setVisible(true); // Sembunyikan layer
                }
            });
        }
    });
}


// Parameter lengkap
const fullParameters = `
    <option value="">Pilih Parameter</option>
    <option value="Keterangan">Tingkat Kesesuaian</option>
    <option value="Temperatur">Temperatur</option>
    <option value="Bulankering">Jumlah Bulan Tanpa Hujan (1 tahun)</option>
    <option value="Drainase">Drainase</option>
    <option value="Tekstur">Tekstur Tanah</option>
    <option value="Lereng">Lereng</option>
    <option value="Erosi">Bahaya Erosi</option>
    <option value="Banjir">Banjir</option>
    <option value="Kapanewon">Lokasi Kapanewon</option>
    <option value="Jenislahan">Jenis Lahan</option>
`;

// Parameter terbatas untuk layer non Keslah_padi, Keslah_jagung, Keslah_kedelai
const limitedParameter = `
    <option value="">Pilih Parameter</option>
    <option value="Keterangan">Tingkat Kesesuaian</option>
    <option value="Kapanewon">Lokasi Kapanewon</option>
    <option value="Jenislahan">Jenis Lahan</option>
`;

document.getElementById('layer').addEventListener('change', function () {
    const selectedLayer = this.value;
    const parameterDropdown = document.getElementById('parameter');

    // Cek layer yang dipilih
    if (selectedLayer === 'Padi' || selectedLayer === 'Jagung' || selectedLayer === 'Kedelai') {
        // Tampilkan semua parameter
        parameterDropdown.innerHTML = fullParameters;
    } else {
        // Tampilkan hanya "Tingkat Kesesuaian"
        parameterDropdown.innerHTML = limitedParameter;
    }
});

// Ubah input berdasarkan parameter yang dipilih
document.getElementById('parameter').addEventListener('change', function () {
    const parameter = this.value;
    const inputContainer = document.getElementById('input-container');
    // const listGroup = document.getElementById('info-list-information'); // Tambahkan div ini di HTML

    // listGroup.innerHTML = '';

    if (parameter === 'Drainase') {
        inputContainer.innerHTML = `
            <select id="value" class="form-select-sm mb-3" style="width: 100%; font-family:'Nunito'; font-size:14px;" placeholder=""Masukkan nilai..">
                <option value="" selected>Pilih kondisi drainase</option>
                <option value="sangat terhambat">Sangat Terhambat</option>
                <option value="terhambat">Terhambat</option>
                <option value="agak terhambat">Agak Terhambat</option>
                <option value="agak baik">Agak Baik</option>
                <option value="baik">Baik</option>
                <option value="agak cepat">Agak Cepat</option>
                <option value="cepat">Cepat</option>
            </select>
        `;
    } else if (parameter === 'Tekstur') {
        inputContainer.innerHTML = `
            <select id="value" name="tekstur" class="form-select-sm mb-3" style="width: 100%; font-family:'Nunito'; font-size:14px;" placeholder=""Masukkan nilai..">
                <option value="" selected>Pilih kondisi tekstur tanah</option>
                <option value="kasar">Kasar</option>
                <option value="agak kasar">Agak Kasar</option>
                <option value="sedang">Sedang</option>
                <option value="agak halus">Agak Halus</option>
                <option value="halus">Halus</option>
            </select>
        `;
    } else if (parameter === 'Lereng') {
        inputContainer.innerHTML = `
            <select id="value" name="lereng" class="form-select-sm mb-3" style="width: 100%; font-family:'Nunito'; font-size:14px;" placeholder="Masukkan nilai...">
                <option value="" selected>Pilih kemiringan lereng</option>
                <option value="datar">Datar</option>
                <option value="agak landai">Agak Landai</option>
                <option value="landai">Landai</option>
                <option value="agak curam">Agak Curam</option>
                <option value="curam">Curam</option>
            </select>
        `;
    } else if (parameter === 'Erosi') {
        inputContainer.innerHTML = `
            <select id="value" name="erosi" class="form-select-sm mb-3 col-sm" style="width: 100%; font-family:'Nunito'; font-size:14px;" placeholder="Masukkan nilai...">
                <option value="" selected>Pilih bahaya erosi</option>
                <option value="sangat ringan">Sangat Ringan</option>
                <option value="ringan">Ringan</option>
                <option value="sedang">Sedang</option>
                <option value="berat">Berat</option>
                <option value="sangat berat">Sangat Berat</option>
            </select>
        `;
    } else if (parameter === 'Banjir') {
        inputContainer.innerHTML = `
            <select id="value" name="banjir" class="form-select-sm mb-3" style="width: 100%; font-family:'Nunito'; font-size:14px;" placeholder="Masukkan nilai...">
                <option value="" selected>Pilih bahaya banjir</option>
                <option value="tidak ada">Tidak Ada</option>
                <option value="sangat ringan">Sangat Ringan</option>
                <option value="ringan">Ringan</option>
                <option value="sedang">Sedang</option>
                <option value="agak berat">Agak Berat</option>
                <option value="berat">Berat</option>
            </select>
        `;
    } else if (parameter === 'Keterangan') {
        inputContainer.innerHTML = `
            <select id="value" class="form-select-sm mb-3 col-sm" style="width: 100%; font-family:'Nunito'; font-size:14px;" placeholder="Masukkan nilai...">
                <option value="" selected>Pilih kesesuaian lahan</option>
                <option value="Sangat Sesuai">Sangat Sesuai</option>
                <option value="Cukup Sesuai">Cukup Sesuai</option>
                <option value="Sesuai Marginal">Sesuai Marginal</option>
                <option value="Tidak Sesuai">Tidak Sesuai</option>
            </select>
        `;
    } else if (parameter === 'Kapanewon') {
        inputContainer.innerHTML = `
            <select id="value" class="form-select-sm mb-3" style="width: 100%; font-family:'Nunito'; font-size:14px;" placeholder="Masukkan nilai...">
                <option value="" selected>Pilih Kapanewon</option>
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
        `;
    } else if (parameter === 'Jenislahan') {
        inputContainer.innerHTML = `
            <select id="value" class="form-select-sm mb-3" style="width: 100%; font-family:'Nunito'; font-size:14px;" placeholder="Masukkan nilai...">
                <option value="" selected>Pilih lahan</option>
                <option value="Sawah Irigasi">Sawah Irigasi</option>
                <option value="Sawah Tadah Hujan">Sawah Tadah Hujan</option>
            </select>
        `;
    }
    else {
        inputContainer.innerHTML = `
            <input type="text" id="value" class="form-control-sm mb-3 col-sm" style="width: 270px;" placeholder="Masukkan nilai...">
        `;
    }
});


const containerQuery = document.getElementById('popupqueryparam');
const contentQuery = document.getElementById('popupqueryparam-content');
const closerQuery = document.getElementById('popupqueryparam-closer');

const overlayInfoQuery = new Overlay({
    element: containerQuery,
    autoPan: true,
    autoPanAnimation: {
        duration: 250
    },
});
map.addOverlay(overlayInfoQuery);

closerQuery.onclick = function () {
    containerQuery.style.display = 'none';
    overlayInfoQuery.setPosition(undefined);
    closerQuery.blur();
    selectPolygonQuery1.getFeatures().clear(); // Hapus pemilihan fitur
    return false;
};

// function showToast(message) {
//     const toast = document.getElementById('toast');
//     toast.innerHTML = message; // Set pesan toast
//     toast.classList.add('show');
    
//     // Hilangkan toast setelah 3 detik
//     setTimeout(() => {
//         toast.classList.remove('show');
//     }, 3000);
// }

// Fungsi untuk mendapatkan warna simbologi berdasarkan layer dan kelas kesesuaian
function getSymbologyQuery1(layer, suitabilityClass) {
    const symbology = {
        'Padi': {
            'Sangat Sesuai': 'rgba(87, 169, 6, 0.3)', // Hijau untuk Sangat Sesuai
            'Cukup Sesuai': 'rgba(34, 238, 8, 0.3)', // Oranye untuk Cukup Sesuai
            'Sesuai Marginal': 'rgba(255, 255, 0, 0.3)', // Merah untuk Sesuai Marginal
            'Tidak Sesuai': 'rgba(255, 0, 0, 0.3)' // Abu-abu untuk N
        },
        'Jagung': {
            'Sangat Sesuai': 'rgba(87, 169, 6, 0.3)', // Biru untuk Sangat Sesuai
            'Cukup Sesuai': 'rgba(34, 238, 8, 0.3)', // Indigo untuk Cukup Sesuai
            'Sesuai Marginal': 'rgba(255, 255, 0, 0.3)', // Violet untuk Sesuai Marginal
            'Tidak Sesuai': 'rgba(255, 0, 0, 0.3)' // Abu-abu gelap untuk N
        },
        'Kedelai': {
            'Sangat Sesuai': 'rgba(87, 169, 6, 0.3)', // Hijau Tua untuk Sangat Sesuai
            'Cukup Sesuai': 'rgba(34, 238, 8, 0.3)', // Emas untuk Cukup Sesuai
            'Sesuai Marginal': 'rgba(255, 255, 0, 0.3)', // Cokelat untuk Sesuai Marginal
            'Tidak Sesuai': 'rgba(255, 0, 0, 0.3)' // Abu-abu gelap untuk N
        },
        'Padijagung': {
            'Sangat Sesuai': 'rgba(87, 169, 6, 0.3)', // Hijau muda
            'Cukup Sesuai': 'rgba(34, 238, 8, 0.3)', // Oranye
            'Sesuai Marginal': 'rgba(255, 255, 0, 0.3)', // Merah
            'Tidak Sesuai': 'rgba(255, 0, 0, 0.3)' // Abu-abu gelap
        },
         'Padikedelai': {
            'Sangat Sesuai': 'rgba(87, 169, 6, 0.3)', // Hijau muda
            'Cukup Sesuai': 'rgba(34, 238, 8, 0.3)', // Oranye
            'Sesuai Marginal': 'rgba(255, 255, 0, 0.3)', // Salmon
            'Tidak Sesuai': 'rgba(255, 0, 0, 0.3)' // Abu-abu perak
        },
         'Jagungkedelai': {
            'Sangat Sesuai': 'rgba(87, 169, 6, 0.3)', // Hijau muda
            'Cukup Sesuai': 'rgba(34, 238, 8, 0.3)', // Oranye
            'Sesuai Marginal': 'rgba(255, 255, 0, 0.3)', // Salmon
            'Tidak Sesuai': 'rgba(255, 0, 0, 0.3)' // Abu-abu perak
        },
        'Pajale': {
            'Sangat Sesuai': 'rgba(87, 169, 6, 0.3)', // Hijau terang
            'Cukup Sesuai': 'rgba(34, 238, 8, 0.3)', // Kuning emas
            'Sesuai Marginal': 'rgba(255, 255, 0, 0.3)', // Salmon muda
            'Tidak Sesuai': 'rgba(255, 0, 0, 0.3)' // Slate abu-abu
        }
        // Tambah simbol untuk layer lain jika diperlukan
    };
    return symbology[layer][suitabilityClass];
}

const labels = {
    'Padi': 'Padi',
    'Jagung': 'Jagung',
    'Kedelai': 'Kedelai',
    'Padijagung': 'Padi & Jagung',
    'Padikedelai': 'Padi & Kedelai',
    'Jagungkedelai': 'Jagung & Kedelai',
    'Pajale': 'Padi, Jagung & Kedelai'
};

// Fungsi untuk mendapatkan label dari layer
function getLayerLabel(layer) {
    return labels[layer] || layer; // Mengembalikan label jika ditemukan, atau nama layer asli jika tidak
}

// Variable to store the vector layer created from the query
let vectorLayerQuery1 = null;
// Variable untuk menyimpan fitur yang dipilih
let selectPolygonQuery1 = null;

document.getElementById('queryButton').addEventListener('click', function () {
    const layer = document.getElementById('layer').value;
    const parameter = document.getElementById('parameter').value;
    const value = document.getElementById('value').value;

    // Validasi input
    if (!layer || !parameter || !value) {
        alert('Silakan lengkapi semua input.');
        return;
    }

    overlayInfoQuery.setPosition(undefined);

    // map.addLayer(adminkec);

    // Query ke backend
    fetch(`/query?layer=${layer}&parameter=${parameter}&value=${value}`)
        .then(response => response.json())
        .then(data => {
            currentIcons = {};
            updateLegend(); // Perbarui legenda setelah mengosongkan ikon sebelumnya

            // console.log("Data hasil query:", data); // Lihat apakah data yang diharapkan ada
            // if (data && data.length > 0) {
            //     updateLegend();  // Pastikan updateLegend hanya dipanggil jika ada data
            // } else {
            //     console.log("Tidak ada data untuk ditampilkan di legenda");
            // }
           

            //  // Hapus layer yang ada
            //  map.getLayers().forEach(layer => {
            //     if (layer.get('name') === 'suitabilityLayer') {
            //         map.removeLayer(layer);
            //     }
            // });           

            // Hapus layer yang ada jika sudah ada sebelumnya
            if (vectorLayerQuery1) {
                map.removeLayer(vectorLayerQuery1);
            }

            if (selectPolygonQuery1 !== null) {
                map.removeInteraction(selectPolygonQuery1);
            }

            const features = new GeoJSON().readFeatures(data, {
                featureProjection: 'EPSG:4326'
            });
            
           // Contoh penggunaan dalam kondisi validasi fitur
            if (!features.length) {
                const layerLabel = getLayerLabel(layer); // Ambil label layer yang sesuai
                Swal.fire({
                    toast: true,
                    icon: 'warning',
                    title: 'Tidak ada lahan dengan kelas "Sangat Sesuai" untuk tanaman ' + layerLabel + '.',
                    position: 'center',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                updateLegendContent([]);
                return; // Keluar jika tidak ada fitur yang valid
            }
            
            const vectorSource = new VectorSource({
                features: features
            });
            
            // Buat layer vektor
            vectorLayerQuery1 = new VectorLayer({
                source: vectorSource,
                style: function (feature) {
                    const suitabilityClass = feature.get('keterangan');
                    const plantType = feature.get('plantType');
                    const fillColor = getSymbologyQuery1(plantType, suitabilityClass);
                                        
                    let iconUrlLegendQuery1;

                    if (layer === 'Padi') {
                        if (suitabilityClass === 'Sangat Sesuai') {
                            iconUrlLegendQuery1 = '/icon/icon-jagung-s2.png';
                        } else if (suitabilityClass === 'Cukup Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_pd_s2.png';
                        } else if (suitabilityClass === 'Sesuai Marginal') {
                            iconUrlLegendQuery1 = '/icon/legend_pd_s3.png';
                        } else if (suitabilityClass === 'Tidak Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_pd_n.png';
                        }
                    }
                    if (layer === 'Jagung') {
                        if (suitabilityClass === 'Sangat Sesuai') {
                            iconUrlLegendQuery1 = '/icon/icon-jagung-s2.png'; 
                        } else if (suitabilityClass === 'Cukup Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_jg_s2.png';
                        } else if (suitabilityClass === 'Sesuai Marginal') {
                            iconUrlLegendQuery1 = '/icon/legend_jg_s3.png';
                        } else if (suitabilityClass === 'Tidak Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_jg_n.png';
                        }
                    }
                    if (layer === 'Kedelai') {
                        if (suitabilityClass === 'Sangat Sesuai') {
                            iconUrlLegendQuery1 = '/icon/icon-jagung-s2.png';
                        } else if (suitabilityClass === 'Cukup Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_kd_s2.png';
                        } else if (suitabilityClass === 'Sesuai Marginal') {
                            iconUrlLegendQuery1 = '/icon/legend_kd_s3.png';
                        } else if (suitabilityClass === 'Tidak Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_kd_n.png';
                        }
                    }
                    if (layer === 'Padijagung') {
                        if (suitabilityClass === 'Sangat Sesuai') {
                            iconUrlLegendQuery1 = '/icon/icon-jagung-s2.png';
                        } else if (suitabilityClass === 'Cukup Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_pj_s2.png';
                        } else if (suitabilityClass === 'Sesuai Marginal') {
                            iconUrlLegendQuery1 = '/icon/legend_pj_s3.png';
                        } else if (suitabilityClass === 'Tidak Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_pj_n.png';
                        }
                    }
                    if (layer === 'Padikedelai') {
                        if (suitabilityClass === 'Sangat Sesuai') {
                            iconUrlLegendQuery1 = '/icon/icon-jagung-s2.png';
                        } else if (suitabilityClass === 'Cukup Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_pk_s2.png';
                        } else if (suitabilityClass === 'Sesuai Marginal') {
                            iconUrlLegendQuery1 = '/icon/legend_pk_s3.png';
                        } else if (suitabilityClass === 'Tidak Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_pk_n.png';
                        }
                    }
                    if (layer === 'Jagungkedelai') {
                        if (suitabilityClass === 'Sangat Sesuai') {
                            iconUrlLegendQuery1 = '/icon/icon-jagung-s2.png';
                        } else if (suitabilityClass === 'Cukup Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_jk_s2.png';
                        } else if (suitabilityClass === 'Sesuai Marginal') {
                            iconUrlLegendQuery1 = '/icon/legend_jk_s3.png';
                        } else if (suitabilityClass === 'Tidak Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_jk_n.png';
                        }
                    }
                    if (layer === 'Pajale') {
                        if (suitabilityClass === 'Sangat Sesuai') {
                            iconUrlLegendQuery1 = '/icon/icon-jagung-s2.png';
                        } else if (suitabilityClass === 'Cukup Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_pjk_s2.png';
                        } else if (suitabilityClass === 'Sesuai Marginal') {
                            iconUrlLegendQuery1 = '/icon/legend_pjk_s3.png';
                        } else if (suitabilityClass === 'Tidak Sesuai') {
                            iconUrlLegendQuery1 = '/icon/legend_pjk_n.png';
                        }
                    }

                    // Tambahkan ikon ke currentIcons jika pencarian aktif
                    if (isSearchActive && iconUrlLegendQuery1) {
                        currentIcons[suitabilityClass] = iconUrlLegendQuery1;
                        // console.log("Menambahkan ke currentIcons:", suitabilityClass, iconUrlLegendQuery1); // Log penambahan
                        // Perbarui legenda
                        updateLegend(); 
                    }
                    
                    return new Style({
                        fill: new Fill({
                            color: fillColor
                        }),
                        stroke: new Stroke({
                            color: '#000000',
                            width: 1
                        }),
                        // image: iconUrlLegendQuery1 ? new Icon({
                        //     src: iconUrlLegendQuery1,
                        //     scale: 0.3 // Sesuaikan skala agar ukuran ikon sesuai
                        // }) : null
                    });
                }
            });
            vectorLayerQuery1.set('name', 'suitabilityLayer');
            map.addLayer(vectorLayerQuery1);

            showSearchIcons();
            updateLegend(); 

            // Pusatkan peta pada fitur baru (jika diperlukan)
            const extent = vectorSource.getExtent();
            map.getView().fit(extent, { padding: [20, 20, 20, 20], maxZoom: 15 });
            

            // Gaya untuk fitur yang disorot
            const highlightStyleQuery1 = new Style({
                fill: new Fill({
                    color: 'rgba(5, 0, 0, 0.4)' // Warna merah untuk fitur yang disorot
                }),
                stroke: new Stroke({
                    color: '#ffcc33',
                    width: 2
                })
            });

            // Inisialisasi interaksi select
            selectPolygonQuery1 = new Select({
                style: highlightStyleQuery1,
                condition: click,
                layers: [vectorLayerQuery1]
            });

            map.addInteraction(selectPolygonQuery1);

            // Menangani event fitur yang dipilih
            selectPolygonQuery1.on('select', function (event) {
                const selectedFeatures = event.selected;

                if (selectedFeatures.length > 0) {
                    const clickedFeature = selectedFeatures[0]; // Ambil fitur yang dipilih
                    const geometry = clickedFeature.getGeometry();
                    // const coordinates = geometry.getCoordinates(); // Get feature coordinates
                    // const firstCoordinate = coordinates[0][0][0];

                     // Cek tipe geometry untuk memastikan pemanggilan getInteriorPoint
                    let coordinates;
                    if (geometry.getType() === 'Polygon' || geometry.getType() === 'MultiPolygon') {
                        // Mendapatkan batas dan menghitung pusat dari geometri
                        const extent = geometry.getExtent();
                        coordinates = getCenter(extent); // Mendapatkan pusat dari extent
                    }
                   
                    // Tampilkan popup dengan informasi fitur
                    const plantType = clickedFeature.get('layerLabel');
                    const luas = clickedFeature.get('luas_pl');
                    const pl = clickedFeature.get('pl');
                    const suitability = clickedFeature.get('keterangan');
                    // const deskripsi = clickedFeature.get('deskripsi');
                    const kapanewon = clickedFeature.get('kapanewon');

                    // const formattedLuas = parseFloat(luas).toFixed(2);
                    const formattedLuas = Number(parseFloat(luas).toFixed(2)).toLocaleString('id-ID');


                    navigator.geolocation.getCurrentPosition((position) => {
                        const userLat = position.coords.latitude;
                        const userLon = position.coords.longitude;
                        const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLon}&destination=${coordinates[1]},${coordinates[0]}`;

                        contentQuery.innerHTML = `
                        <strong style="font-family: Nunito;">Informasi Tanaman ${plantType}</strong>
                        <table style="width:100%; border-collapse: collapse; margin-top: 5px; font-family: Nunito;">
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">Kapanewon</td>
                                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">${kapanewon}</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">Jenis Lahan</td>
                                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">${pl}</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">Luas</td>
                                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">${formattedLuas} m<sup>2</sup></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">Kesesuaian Lahan</td>
                                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">${suitability}</td>
                            </tr>

                        </table>
                        <button id="route-button" class="btn btn-primary" style="width: 100%; margin-top:2px; font-family:Nunito; font-size:14px;">Menuju Lokasi</button>                          
                        `;
                        overlayInfoQuery.setPosition(coordinates);
                         // Posisikan popup di tengah polygon
                        // overlayInfoQuery.setPosition(geometry.getInteriorPoint().getCoordinates());
                        overlayInfoQuery.getElement().style.display = 'block';

                        document.getElementById('route-button').addEventListener('click', () => {
                            window.open(googleMapsUrl, '_blank'); // Buka Google Maps di tab baru
                        });

                        map.getView().setCenter(coordinates);
                        // map.getView().setCenter(firstCoordinate);
                        map.getView().setZoom(17);
                    });
                } else {
                    // Jika tidak ada fitur yang dipilih
                    overlayInfoQuery.getElement().style.display = 'none';
                }
            });

            // Tutup popup jika klik di luar
            map.on('click', function () {
                overlayInfoQuery.getElement().style.display = 'none';
                selectPolygonQuery1.getFeatures().clear(); // Hapus pemilihan fitur
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
});            

// Event Listener untuk tombol "Batal"
document.getElementById('cancelqueryButton').addEventListener('click', function () {
    document.getElementById('layer').value = '';
    document.getElementById('parameter').value = '';
    document.getElementById('value').value = '';
    // document.getElementById('value').placeholder = 'Masukkan Nilai';
    document.getElementById('queryModal').style.display = 'none';
    if (vectorLayerQuery1) {
        map.removeLayer(vectorLayerQuery1);
        vectorLayerQuery1 = null; // Reset referensi layer
    }    
    if (selectPolygonQuery1 !== null) {
        map.removeInteraction(selectPolygonQuery1);
    }
    map.removeOverlay(overlayInfoQuery);
    currentIcons = {};
    updateLegend();
    clearSearchIcons();
    showAllLayers();
});


/** Query by Area */ 
// Fungsi untuk melakukan query dan mendapatkan GeoJSON serta zoom ke area yang dipilih
function queryKapanewon(kapanewon) {
    fetch(`/querypilihArea?kapanewon=${kapanewon}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const geojsonObject = data.data[0].geojson;

                // Hapus fitur sebelumnya dari vectorSource
                vectorKapSource.clear();

                // Parse GeoJSON dan tambahkan ke vectorSource
                const format = new GeoJSON();
                const features = format.readFeatures(geojsonObject, {
                    featureProjection: 'EPSG:4326' // Sesuaikan dengan projection yang digunakan
                });
                vectorKapSource.addFeatures(features);

                // Zoom ke fitur yang baru ditambahkan
                const extent = vectorKapSource.getExtent();
                map.getView().fit(extent, { duration: 1000, maxZoom: 16 });

            } else {
                alert('Data tidak ditemukan');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Style untuk area yang dipilih
const selectedKapStyle = new Style({
    stroke: new Stroke({
        color: '#ffcc33', // Warna garis kuning
        width: 3
    }),
    // fill: new Fill({
    //     color: 'rgba(255, 204, 51, 0.5)' // Warna isian kuning dengan transparansi
    // })
});

// Sumber data GeoJSON untuk menyimpan fitur yang dipilih
const vectorKapSource = new VectorSource();

// Layer untuk menampilkan area yang dipilih
const vectorKapLayer = new VectorLayer({
    source: vectorKapSource,
    style: selectedKapStyle
});

// Menambahkan layer ke peta
map.addLayer(vectorKapLayer);

// Fungsi untuk menangani perubahan dropdown kapanewon
document.getElementById('layerkapanewon').addEventListener('change', function () {
    const selectedKapanewon = this.value;

    if (selectedKapanewon) {
        // Tampilkan layer kapanewon untuk hasil pencarian baru
        vectorKapLayer.setVisible(true);

        // Panggil fungsi untuk query kapanewon dan tambahkan fitur simbol
        queryKapanewon(selectedKapanewon);
    }
});

// Sumber data GeoJSON untuk menyimpan fitur yang digambar
const drawnQuerySource = new VectorSource();

// Layer untuk menampilkan area yang digambar
const drawnQueryLayer = new VectorLayer({
    source: drawnQuerySource,
    style: new Style({
        stroke: new Stroke({
            color: 'rgba(0, 0, 255, 6)', // Warna garis biru
            width: 2
        }),
        fill: new Fill({
            color: 'rgba(0, 0, 255, 0.1)' // Warna isian biru dengan transparansi
        })
    })
});

// Menambahkan layer untuk area yang digambar ke peta
map.addLayer(drawnQueryLayer);

// Aktifkan fitur gambar polygon dan tampilkan popup informasi
let draw; // Variabel untuk fitur gambar
const popup = new Overlay({
    element: document.getElementById('popup'),
    autoPan: true
});
map.addOverlay(popup);

// Event listener untuk tombol "Pilih Area"
document.getElementById('drawqueryButton').addEventListener('click', function () {

    // Jika ada interaksi menggambar yang sudah aktif, hapus dulu sebelum menambahkan yang baru
    if (draw) {
        map.removeInteraction(draw);
    }

    draw = new Draw({
        source: drawnQuerySource, // Sumber layer untuk menyimpan hasil gambar
        type: 'Polygon'
    });
    map.addInteraction(draw);

    // Hapus gambar sebelumnya saat mulai menggambar yang baru
    draw.on('drawstart', function () {
        drawnQuerySource.clear(); // Clear previous drawings before starting a new one
    });

    // Ketika selesai menggambar, tampilkan popup
    draw.on('drawend', function (event) {
        var geom = event.feature.getGeometry();
        var area = getArea(geom,{projection: 'EPSG:4326'}); // Hitung luas dalam meter persegi
        var output;

        // Konversi luas ke hektar atau km2 sesuai kebutuhan
        if (area > 10000) {
            output = (area / 10000).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ha'; // Hektar
        } else {
            output = area.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' m²'; // Meter persegi
        }

        const polygon = event.feature.getGeometry();
        // const drawnFeature = event.feature;
        // const polygon = event.feature.getGeometry();
        const extent = polygon.getExtent();
        const info = getAreaInfo(extent, output); // You will implement this function to retrieve area info
        showPopupArea(event.feature.getGeometry().getInteriorPoint().getCoordinates(), info);
    });
});

// Sumber vektor untuk data GeoJSON kapanewon
const keslahallQuerySource = new VectorSource({
    url: '/geojson/sawahkeslah.geojson', // Ganti dengan path ke file GeoJSON Anda
    format: new GeoJSON(),
});

// Layer vektor untuk kapanewon
const keslahallQuery = new VectorLayer({
    source: keslahallQuerySource,
    style: new Style({
        stroke: new Stroke({
            color: 'rgba(0, 0, 0, 0)', // Warna garis biru
            width: 0,
        }),
        // fill: new Fill({
        //     color: 'rgba(0, 0, 255, 0.1)' // Warna isian biru dengan transparansi
        // })
    })
});
map.addLayer(keslahallQuery)

// Function to show popup
function showPopupArea(coordinate, content) {
    const container = document.getElementById('popup-query');
    const contentElement = document.getElementById('popup-content-query');
    const closer = document.getElementById('popup-closer-query');

    contentElement.innerHTML = content;
    container.style.display = 'block';

    popupOverlay.setPosition(coordinate);
    popupOverlay.setOffset([0, -10]);

    closer.onclick = function () {
        container.style.display = 'none';
        popupOverlay.setPosition(undefined);
        closer.blur();
        drawnQuerySource.clear(); // Clear previous drawings before starting a new one
        return false;
    };
}

// Fungsi untuk mendapatkan informasi area berdasarkan batas (extent)
function getAreaInfo(extent, measuredArea) {
    let info = '';
    let displayedKapanewons = {};  // Menyimpan kapanewon yang sudah ditampilkan

    // Mendapatkan semua fitur di sumber kapanewon
    const features = keslahallQuerySource.getFeatures();

    // Memeriksa apakah fitur berada di dalam batas area yang digambar
    features.forEach(function (feature) {
        const featureExtent = feature.getGeometry().getExtent();
        const kapanewonName = feature.get('kapanewon');  // Nama Kapanewo

        // Periksa apakah fitur berada di dalam extent yang digambar dan apakah kapanewon sudah ditampilkan
        if (intersects(extent, featureExtent) && !displayedKapanewons[kapanewonName]) {
            // info += '<div class="section">';
            info += '<div class="header" style="font-family: Nunito; font-weight: bold; ">Informasi Lahan</div>';
            
            info += '<table style="width:100%; border-collapse: collapse; font-family: Nunito;">';

            // Kapanewon
            info += `
                <tr>
                    <td style="border: 1px solid #ddd; padding: 5px; font-weight: bold;">Kapanewon</td>
                    <td style="border: 1px solid #ddd; padding: 5px;">${kapanewonName}</td>
                </tr>
            `;

            // Jenis Lahan
            info += `
                <tr>
                    <td style="border: 1px solid #ddd; padding: 5px; font-weight: bold;">Lahan</td>
                    <td style="border: 1px solid #ddd; padding: 5px;">${feature.get('pl') || 'Tidak tersedia'}</td>
                </tr>
            `;

            // Luas
            info += `
                <tr>
                    <td style="border: 1px solid #ddd; padding: 5px; font-weight: bold;">Luas</td>
                    <td style="border: 1px solid #ddd; padding: 5px;">${measuredArea}</td>
                </tr>
            `;

            // Kesesuaian Lahan (sub-header)
            info += `
                <tr>
                    <td colspan="2" style="border: 1px solid #ddd; padding: 5px; font-weight: bold; text-align: center; background-color: #f2f2f2;">Kesesuaian Lahan</td>
                </tr>
            `;

            // Padi
            info += `
                <tr>
                    <td style="border: 1px solid #ddd; padding: 5px;">Padi</td>
                    <td style="border: 1px solid #ddd; padding: 5px;">${feature.get('ket_padi') || 'Tidak tersedia'}</td>
                </tr>
            `;

            // Jagung
            info += `
                <tr>
                    <td style="border: 1px solid #ddd; padding: 5px;">Jagung</td>
                    <td style="border: 1px solid #ddd; padding: 5px;">${feature.get('ket_jag') || 'Tidak tersedia'}</td>
                </tr>
            `;

            // Kedelai
            info += `
                <tr>
                    <td style="border: 1px solid #ddd; padding: 5px;">Kedelai</td>
                    <td style="border: 1px solid #ddd; padding: 5px;">${feature.get('ket_ked') || 'Tidak tersedia'}</td>
                </tr>
            `;

            info += '</table>';
            // info += '</div>';

            // Tandai kapanewon sudah ditampilkan
            displayedKapanewons[kapanewonName] = true;
        }
    });

    // Jika tidak ada fitur yang ditemukan di dalam batas
    if (info === '') {
        info = '<p>Tidak ada lahan sawah yang ditemukan dalam area ini.</p>';
    }

    return info;
}

// Overlay for popup
const popupOverlay = new Overlay({
    element: document.getElementById('popup-query'),
    autoPan: true,
    autoPanAnimation: { duration: 250 },
    zIndex: 1000,
});
map.addOverlay(popupOverlay);

// Event Listener untuk tombol "Batal"
document.getElementById('canceldrawqueryButton').addEventListener('click', function () {
    document.getElementById('layerkapanewon').value = '';
    document.getElementById('queryModal').style.display = 'none';
    map.removeInteraction(draw); // Hentikan mode gambar
    // map.removeOverlay(popupOverlay);
    popupOverlay.setPosition(undefined);
    vectorKapLayer.setVisible(false);
    drawnQuerySource.clear(); // Clear previous drawings before starting a new one
    clearSearchIcons();
    showAllLayers();
});

/** Query by Parameter */ 
let previousLayer;
let previousMarkerLayerPadi;
let previousMarkerLayerJagung;
let previousMarkerLayerKedelai;

const containerQuery3 = document.getElementById('popupqueryparamatt');
const contentQuery3 = document.getElementById('popupqueryparamatt-content');
const closerQuery3 = document.getElementById('popupqueryparamatt-closer');

const overlayInfoQuery3 = new Overlay({
    element: containerQuery3,
    autoPan: true,
    autoPanAnimation: {
        duration: 250
    },
    zIndex:1000,
});
map.addOverlay(overlayInfoQuery3);

closerQuery3.onclick = function () {
    containerQuery3.style.display = 'none';
    overlayInfoQuery3.setPosition(undefined);
    closerQuery3.blur();
    return false;
};

// Fungsi untuk menampilkan popup dengan informasi marker
function showMarkerPopup(clickedFeature) {
    const plantType = clickedFeature.get('plant_type');
    const kapanewon = clickedFeature.get('kapanewon');
    const suitability = clickedFeature.get('keterangan');
    const luas = clickedFeature.get('luas_pl');
    const formattedLuas = Number(parseFloat(luas).toFixed(2)).toLocaleString('id-ID');
    const coordinates = clickedFeature.getGeometry().getCoordinates();

    // Mendapatkan lokasi pengguna untuk rute ke marker
    navigator.geolocation.getCurrentPosition(function (position) {
        const userLat = position.coords.latitude;
        const userLon = position.coords.longitude;
        const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLon}&destination=${coordinates[1]},${coordinates[0]}`;

        // Mengisi konten popup
        contentQuery3.innerHTML = `
        <strong style="font-family: Nunito;">Informasi Lokasi</strong>
        <table style="width:100%; border-collapse: collapse; margin-top: 5px; font-family: Nunito;">
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">Kapanewon</td>
                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">${kapanewon}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">Jenis Tanaman</td>
                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">${plantType}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">Luas</td>
                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">${formattedLuas} m<sup>2</sup></td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">Kesesuaian Lahan</td>
                <td style="border: 1px solid #ddd; padding: 8px; font-size:14px;">${suitability}</td>
            </tr>
        </table>
        <button id="route-button" class="btn btn-primary" style="width: 100%; margin-top:2px; font-family:Nunito; font-size:14px;">Menuju Lokasi</button>                          
        `;

         // Tampilkan popup dengan mengubah display menjadi 'block'
        overlayInfoQuery3.getElement().style.display = 'block';
        overlayInfoQuery3.setPosition(coordinates);

        // Event listener untuk tombol "Menuju Lokasi"
        document.getElementById('route-button').addEventListener('click', function () {
            window.open(googleMapsUrl, '_blank');
        });
        // Pusatkan peta ke lokasi marker
        map.getView().setCenter(coordinates);
        map.getView().setZoom(17);
        // map.getView().animate({ center: coordinates, zoom: 17 });
    });               
}

// Tampilkan hasil query
document.getElementById('query-attparamsButton').addEventListener('click', function () {
    const temperature = document.getElementById('temperature').value;
    const drainage = document.getElementById('drainage').value;
    const texture = document.getElementById('texture').value;
    const slope = document.getElementById('slope').value;
    const erosion = document.getElementById('erosion').value;
    const flood = document.getElementById('flood').value;

    const markerIcons = {
        Padi: {
            'Sangat Sesuai': '/icon/icon-padi-s2.png',
            'Cukup Sesuai': '/icon/icon-padi-s2.png',
            'Sesuai Marginal': '/icon/icon-padi-s3.png',
            'Tidak Sesuai': '/icon/icon-padi-n.png'
        },
        Jagung: {
           'Sangat Sesuai': '/icon/icon-jagung-s2.png',
            'Cukup Sesuai': '/icon/icon-jagung-s2.png',
            'Sesuai Marginal': '/icon/icon-jagung-s3.png',
            'Tidak Sesuai': '/icon/icon-jagung-n.png'
        },
        Kedelai: {
           'Sangat Sesuai': '/icon/icon-kedelai-s2.png',
            'Cukup Sesuai': '/icon/icon-kedelai-s2.png',
            'Sesuai Marginal': '/icon/icon-kedelai-s3.png',
            'Tidak Sesuai': '/icon/icon-kedelai-n.png'
        }
    };
    const legendmarkerIcons = {
        Padi: {
            'Sangat Sesuai': '/icon/legend-padi-s2.png',
            'Cukup Sesuai': '/icon/legend-padi-s2.png',
            'Sesuai Marginal': '/icon/legend-padi-s3.png',
            'Tidak Sesuai': '/icon/legend-padi-n.png'
        },
        Jagung: {
           'Sangat Sesuai': '/icon/legend-jagung-s2.png',
            'Cukup Sesuai': '/icon/legend-jagung-s2.png',
            'Sesuai Marginal': '/icon/legend-jagung-s3.png',
            'Tidak Sesuai': '/icon/legend-jagung-n.png'
        },
        Kedelai: {
           'Sangat Sesuai': '/icon/legend-kedelai-s2.png',
            'Cukup Sesuai': '/icon/legend-kedelai-s2.png',
            'Sesuai Marginal': '/icon/legend-kedelai-s3.png',
            'Tidak Sesuai': '/icon/legend-kedelai-n.png'
        }
    };
        
    // Query ke backend
    fetch(`/queryParameter?temperature=${temperature}&drainage=${drainage}&texture=${texture}&slope=${slope}&erosion=${erosion}&flood=${flood}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            currentIconsQ3 = {};
            updateLegend(); // Perbarui legenda setelah mengosongkan ikon sebelumnya

            // Jika layer sebelumnya ada, hapus dari peta
            if (previousLayer) {
                map.removeLayer(previousLayer);
            }
            if (previousMarkerLayerPadi) {
                map.removeLayer(previousMarkerLayerPadi);
            }
            if (previousMarkerLayerJagung) {
                map.removeLayer(previousMarkerLayerJagung);
            }
            if (previousMarkerLayerKedelai) {
                map.removeLayer(previousMarkerLayerKedelai);
            }

             // Buat vector source untuk polygon
            const polygonSource = new VectorSource({
                features: new GeoJSON().readFeatures(data, {
                    featureProjection: 'EPSG:4326' // Proyeksi untuk OpenLayers
                })
            });

            // Buat vector source untuk marker
            const markerSource = new VectorSource();

            // Membaca fitur polygon dan menambahkan marker
            const offsets = {
                'Kedelai': [0.00005, 0.00005], // Offset lebih kecil untuk Kedelai
                'Padi': [0.00005, -0.00005],    // Offset lebih kecil untuk Padi
                'Jagung': [-0.00005, -0.00005],  // Offset lebih kecil untuk Jagung
            };
            
            const features = polygonSource.getFeatures();

            // Check if there are any features at all
            if (features.length === 0) {
                Swal.fire({
                    toast: true,
                    icon: 'warning',
                    title: 'Tidak ada lahan yang tersedia.',
                    position: 'center',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                updateLegendContent([]);
                return;
            }

            // Create layer for polygons
            const polygonLayer = new VectorLayer({
                source: polygonSource,
                style: new Style({
                    fill: new Fill({
                        color: 'rgba(255, 255, 255, 0.4)' // Default fill color
                    }),
                    stroke: new Stroke({
                        color: 'rgba(0, 200, 0)', // Stroke color
                        width: 1 // Stroke width
                    }),
                    zIndex: 1,
                })
            });
            map.addLayer(polygonLayer);

            const clusterSources = {
                Padi: new Cluster({
                    distance: 40,
                    source: new VectorSource()
                }),
                Jagung: new Cluster({
                    distance: 40,
                    source: new VectorSource()
                }),
                Kedelai: new Cluster({
                    distance: 40,
                    source: new VectorSource()
                })
            };
            features.forEach(feature => {
                const geometry = feature.getGeometry();
                
                // Ambil data yang diperlukan
                const plantType = feature.get('plant_type');
                const ket = feature.get('keterangan');
                const luas = feature.get('luas_pl');
                const kapanewon = feature.get('kapanewon');
                
                // Pastikan data dan ikon valid sebelum melanjutkan
                if (plantType && ket && markerIcons[plantType]?.[ket]) {
                    if (geometry.getType() === 'MultiPolygon') { // Cek apakah geometri adalah MultiPolygon
                        const polygons = geometry.getPolygons(); // Ambil semua poligon dari MultiPolygon
                        
                        polygons.forEach(polygon => {
                            const flatCoords = polygon.getFlatCoordinates(); // Ambil koordinat datar dari poligon
                            const coordinates = [];
                            
                            for (let i = 0; i < flatCoords.length; i += 3) {
                                coordinates.push([flatCoords[i], flatCoords[i + 1]]); // Ambil koordinat lon dan lat
                            }
                            
                            const center = polygon.getInteriorPoint().getCoordinates(); // Ambil titik interior poligon
                            
                            if (center) {
                                // Hitung posisi baru dengan offset
                                const offset = offsets[plantType] || [0, 0]; // Gunakan offset berdasarkan jenis tanaman
                                const newCoordinates = [center[0] + offset[0], center[1] + offset[1]]; // Sesuaikan koordinat
                                
                                // Ambil path ikon berdasarkan jenis tanaman dan keterangan
                                const iconPath = markerIcons[plantType][ket];
                                const iconLegendPath = legendmarkerIcons[plantType][ket];
                                
                                // Buat fitur marker
                                const markerFeature = new Feature({
                                    geometry: new Point(newCoordinates),
                                    plant_type: plantType,
                                    keterangan: ket,
                                    luas_pl: luas,
                                    kapanewon: kapanewon,
                                    type: 'marker'
                                });
            
                                // Tentukan style untuk marker
                                const markerStyle = new Style({
                                    image: new Icon({
                                        src: iconPath,
                                        zIndex: 10,
                                        scale: 0.5
                                    })
                                });
            
                                markerFeature.setStyle(markerStyle);
            
                                // Tambahkan marker ke sumber cluster yang sesuai berdasarkan jenis tanaman
                                clusterSources[plantType].getSource().addFeature(markerFeature);
                                
                                // Tambahkan ikon ke currentIconsQ3 untuk legend
                                if (!currentIconsQ3[plantType]) {
                                    currentIconsQ3[plantType] = {}; // Inisialisasi jika belum ada
                                }
                                currentIconsQ3[plantType][ket] = iconLegendPath; // Simpan ikon berdasarkan jenis tanaman dan keterangan
                                
                                // Perbarui legenda setelah semua marker ditambahkan
                                showSearchIcons();
                            } else {
                                console.error('Invalid center coordinates:', center); // Log jika ada masalah dengan koordinat tengah
                            }
                        }); // End of polygons.forEach
                    } else {
                        console.error('Geometry is not a MultiPolygon:', geometry.getType());
                    }
                }
            });
            
            // Create and style cluster layers for each crop
            const clusterLayers = {
                Padi: new VectorLayer({
                    source: clusterSources.Padi,
                    style: createClusterStyleFunction('Padi')
                }),
                Jagung: new VectorLayer({
                    source: clusterSources.Jagung,
                    style: createClusterStyleFunction('Jagung')
                }),
                Kedelai: new VectorLayer({
                    source: clusterSources.Kedelai,
                    style: createClusterStyleFunction('Kedelai')
                })
            };
            
            // Add cluster layers to the map
            map.addLayer(clusterLayers.Padi);
            map.addLayer(clusterLayers.Jagung);
            map.addLayer(clusterLayers.Kedelai);

            showSearchIcons();
            updateLegend();

            // Simpan referensi layer ini untuk dihapus pada pencarian berikutnya
            previousLayer = polygonLayer;
            previousMarkerLayerPadi = clusterLayers.Padi;
            previousMarkerLayerJagung = clusterLayers.Jagung;
            previousMarkerLayerKedelai = clusterLayers.Kedelai;

            // previousMarkerLayer = markerLayer;

            // Pusatkan peta pada fitur baru (jika diperlukan)
            const extent = polygonSource.getExtent();
            map.getView().fit(extent, { padding: [20, 20, 20, 20], maxZoom: 15 });

            // const highlightStyleQuery3 = new Style({
            //     image: new CircleStyle({
            //         radius: 5,
            //         fill: new Fill({ color: 'rgba(0,0,0, 1)' }), // Normal fill color
            //         stroke: new Stroke({ color: 'rgba(200, 200, 0)', width: 1.5 }) // Normal stroke color
            //     }),
            // });

            // Style function to handle crop-specific cluster styling
            function createClusterStyleFunction(cropType) {
                return function(feature) {
                    const size = feature.get('features').length;
                    let style;

                    if (size > 1) {
                        style = new Style({
                            image: new CircleStyle({
                                radius: 10,
                                zIndex: 10,
                                stroke: new Stroke({ color: '#fff' }),
                                fill: new Fill({ color: cropType === 'Padi' ? '#3399CC' : (cropType === 'Jagung' ? '#cf00ff' : '#f00db5') })
                            }),
                            text: new Text({
                                text: size.toString(),
                                fill: new Fill({ color: '#fff' })
                            })
                        });
                    } else {
                        const originalFeature = feature.get('features')[0];
                        const iconPath = markerIcons[cropType][originalFeature.get('keterangan')];

                        style = new Style({
                            image: new Icon({
                                src: iconPath,
                                zIndex: 10,
                                scale: 0.5,
                                anchor: [0.5, 1]
                            })
                        });
                    }
                    return style;
                };
            }

            // Function to update cluster distance based on zoom
            function updateClusterDisplay() {
                const zoom = map.getView().getZoom();
                const distance = zoom >= 16 ? 0 : 40;
                Object.values(clusterSources).forEach(source => source.setDistance(distance));
            }

            // Listen for zoom changes to update clustering
            map.getView().on('change:resolution', updateClusterDisplay);

            // Event listener untuk memonitor perubahan zoom
            map.getView().on('change:resolution', updateClusterDisplay);

            map.on('click', function (event) {
                let featureFound = false;

                map.forEachFeatureAtPixel(event.pixel, function (feature) {
                    const features = feature.get('features');

                    if (features && features.length === 1) {
                        // If only one marker, show the popup with marker information
                        const clickedFeature = features[0];
                        showMarkerPopup(clickedFeature);
                        featureFound = true;
                    } else if (features && features.length > 1) {
                        // If a cluster of markers is clicked, zoom to the cluster
                        const extent = createEmpty();
                        features.forEach(function (f) {
                            extend(extent, f.getGeometry().getExtent());
                        });

                        // Zoom to the cluster area
                        map.getView().fit(extent, { duration: 500 });
                        featureFound = true;
                    }
                });

                // Sembunyikan popup jika klik di area kosong
                if (!featureFound) {
                    overlayInfoQuery3.getElement().style.display = 'none';
                    overlayInfoQuery3.setPosition(undefined);
                }
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
});

// Event Listener untuk tombol "Batal"
document.getElementById('cancelquery-attparamsButton').addEventListener('click', function () {
    document.getElementById('temperature').value = '';
    document.getElementById('drainage').value = '';
    document.getElementById('texture').value = '';
    document.getElementById('slope').value = '';
    document.getElementById('erosion').value = '';
    document.getElementById('flood').value = '';
    document.getElementById('queryModal').style.display = 'none';
    map.removeLayer(previousLayer);
    map.removeLayer(previousMarkerLayerPadi);
    map.removeLayer(previousMarkerLayerJagung);
    map.removeLayer(previousMarkerLayerKedelai);
    overlayInfoQuery3.setPosition(undefined);
    // map.removeOverlay(overlayInfoParamQuery);
    currentIconsQ3 = {};
    updateLegend();
    clearSearchIcons();
    showAllLayers();
});
// End Query by Parameters

// Function to clear search results and reset inputs
function clearSearchResults() {
    // Reset semua input yang relevan
    document.getElementById('temperature').value = '';
    document.getElementById('drainage').value = '';
    document.getElementById('texture').value = '';
    document.getElementById('slope').value = '';
    document.getElementById('erosion').value = '';
    document.getElementById('flood').value = '';
    document.getElementById('layerkapanewon').value = '';
    document.getElementById('layer').value = '';
    document.getElementById('parameter').value = '';
    document.getElementById('value').value = '';

    // Hapus interaction draw dan popup overlay
    map.removeInteraction(draw);
    if (popupOverlay) {
        popupOverlay.setPosition(undefined);
    }
    // if (overlayInfoParamQuery) {
    //     map.removeOverlay(overlayInfoParamQuery);
    // }
    // Hapus layer dan overlay yang mungkin ada
    if (previousLayer) {
        map.removeLayer(previousLayer);
    }
    if (previousMarkerLayerPadi) {
        map.removeLayer(previousMarkerLayerPadi);
    }
    if (previousMarkerLayerJagung) {
        map.removeLayer(previousMarkerLayerJagung);
    }
    if (previousMarkerLayerKedelai) {
        map.removeLayer(previousMarkerLayerKedelai);
    }
    if (vectorLayerQuery1) {
        map.removeLayer(vectorLayerQuery1);
        vectorLayerQuery1 = null; // Reset referensi layer
    }    
    if (selectPolygonQuery1 !== null) {
        map.removeInteraction(selectPolygonQuery1);
    }
    overlayInfoQuery.setPosition(undefined);
    overlayInfoQuery3.setPosition(undefined);
    
    if (vectorKapLayer) {
        vectorKapLayer.setVisible(false);
    }

    currentIcons = {};
    currentIconsQ3 = {};
    updateLegend();

    // Hapus ikon pencarian dan area gambar
    clearSearchIcons();
    if (drawnQuerySource) {
        drawnQuerySource.clear();
    }

    // // Tampilkan semua layer kembali
    // showAllLayers();
}
document.getElementById('nav-attributes-tab').addEventListener('click', clearSearchResults);
document.getElementById('nav-draw-tab').addEventListener('click', clearSearchResults);
document.getElementById('nav-attparams-tab').addEventListener('click', clearSearchResults);


// CARI LOKASI
// Event listener untuk tombol search
const searchButton = document.getElementById('search');
const searchModal = document.getElementById('searchModal');
searchButton.addEventListener('click', disableInfoPopup);
searchButton.addEventListener('click', () => {
    searchModal.style.display = 'block'; // Tampilkan modal
    document.getElementById('queryModal').style.display = 'none';
    // Highlight info
    if (selectedFeature) {
        clearHighlight();
    }
    popup.getElement().style.display = 'none';
    // Nonaktifkan interaksi pengukuran jika aktif
    if (drawMeasure) {
        map.removeInteraction(drawMeasure);
        drawMeasure = null; // Reset variabel drawMeasure
         // Hapus hasil pengukuran
         sourceMeasurement.clear(); // Menghapus fitur yang digambar
    }
    // Hapus measureTooltip jika ada
    if (measureTooltip) {
        map.removeOverlay(measureTooltip); // Menghapus overlay dari peta
        measureTooltipElement = null; // Reset variabel measureTooltipElement
        measureTooltip = null; // Reset variabel measureTooltip
    }
    // Hapus semua tooltip statis (ol-tooltip-static)
    const staticTooltips = document.querySelectorAll('.ol-tooltip-static');
    staticTooltips.forEach((tooltip) => {
        tooltip.remove(); // Menghapus elemen tooltip dari DOM
    });
    // Optional: Hapus helpTooltip jika ada
    if (helpTooltip) {
        map.removeOverlay(helpTooltip);
        helpTooltipElement = null;
        helpTooltip = null;
    }
    clearSearchResults();
    showAllLayers();
});

// Fungsi untuk validasi longitude
function isValidLongitude(lng) {
    const numLng = parseFloat(lng);
    return numLng >= -180 && numLng <= 180;
}

// Fungsi untuk validasi latitude
function isValidLatitude(lat) {
    const numLat = parseFloat(lat);
    return numLat >= -90 && numLat <= 90;
}
let previousPointGeolocation;
document.getElementById('cariLokasi').addEventListener('click', function () {
    const longitude = parseFloat(document.getElementById('longitude').value);
    const latitude = parseFloat(document.getElementById('latitude').value);

    // popup.getElement().style.display = 'none';

    // Validasi input longitude dan latitude
    if (!isValidLongitude(longitude) || !isValidLatitude(latitude)) {
        Swal.fire({
            toast: true,
            icon: 'warning',
            title: 'Mohon masukkan nilai longitude dan latitude yang valid.',
            position: 'center',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }

    // Mengubah koordinat lon-lat ke proyeksi peta (EPSG:3857 jika peta Anda menggunakan proyeksi ini)
    const coordinate = fromLonLat([longitude, latitude], 'EPSG:4326');

    // Pusatkan peta pada koordinat yang dimasukkan
    map.getView().setCenter(coordinate);
    map.getView().setZoom(12); 

    fetch(`/searchLocation?longitude=${longitude}&latitude=${latitude}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (previousPointGeolocation) {
                map.removeLayer(previousPointGeolocation);
            }

            const features = new GeoJSON().readFeatures(data, {
                featureProjection: 'EPSG:4326'
            });

            if (features.length === 0) {
                if (popup) {
                    popup.setPosition(undefined);  // Menyembunyikan popup
                    popup.getElement().style.display = 'none';  // Menyembunyikan elemen popup
                }
                // Menambahkan marker default
                const defaultMarkerFeature = new Feature(new Point(coordinate));
                const vectorSource = new VectorSource({
                    features: [defaultMarkerFeature]  // Marker default di lokasi input
                });

                // Menambahkan gaya titik pada hasil pencarian
                const style = new Style({
                    image: new CircleStyle({
                        radius: 6, 
                        fill: new Fill({ color: '#000000' }), 
                        stroke: new Stroke({ color: '#ffffff', width: 2 }) 
                    }),
                });

                const vectorLayer = new VectorLayer({
                    source: vectorSource,
                    style: style 
                });
                map.addLayer(vectorLayer);
                previousPointGeolocation = vectorLayer;

                Swal.fire({
                    toast: true,
                    icon: 'warning',
                    title: 'Informasi kesesuaian lahan tidak diketahui!',
                    position: 'center',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                return;
            }

            const vectorSource = new VectorSource({
                features: features
            });

            // Menambahkan gaya titik pada hasil pencarian
            const style = new Style({
                image: new CircleStyle({
                    radius: 6, 
                    fill: new Fill({ color: '#000000' }), 
                    stroke: new Stroke({ color: '#ffffff', width: 2 }) 
                }),
            });

            const vectorLayer = new VectorLayer({
                source: vectorSource,
                style: style 
            });
            map.addLayer(vectorLayer);
            previousPointGeolocation = vectorLayer;

            if (features.length > 0) {
                const coordinates = features[0].getGeometry().getCoordinates();
                popup.setPosition(coordinates);
                document.getElementById('popup').innerHTML = `
                <table style="width:100%; border-collapse: collapse; font-family: Nunito; margin-top: 2px;">
                    <tr>
                        <td colspan="2" style="font-size: 18px; font-weight: bold; text-align: center; border-bottom: 2px solid #ddd;">
                            Informasi Lokasi
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">Kapanewon</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${features[0].get('kapanewon') || 'Tidak tersedia'}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 16px; font-weight: bold; padding: 8px; background-color: #f2f2f2;">Kesesuaian Lahan</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">Padi</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${features[0].get('padi') || 'Tidak tersedia'}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">Jagung</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${features[0].get('jagung') || 'Tidak tersedia'}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">Kedelai</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${features[0].get('kedelai') || 'Tidak tersedia'}</td>
                    </tr>
                </table>
                `;
                popup.getElement().style.display = 'block';

                // <button id="popup-closer" style="margin-top: 10px;">Tutup</button>
                // document.getElementById('popup-closer').onclick = function () {
                //     popup.setPosition(undefined);
                //     popup.getElement().style.display = 'none';
                //     return false;
                // };
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Terjadi kesalahan saat mengambil data lokasi.',
                position: 'center',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
});

document.getElementById('closeModal').addEventListener('click', function () {
    document.getElementById('searchModal').style.display = 'none'; // Menyembunyikan modal
    // Kosongkan nilai latitude dan longitude
    el('longitude').value = ''; // Kosongkan longitude
    el('latitude').value = '';  // Kosongkan latitude
    tracking = false; // Disable tracking
    geolocation.setTracking(tracking); // Stop geolocation tracking
    map.removeLayer(point_geolocation);
    popup.getElement().style.display = 'none';
    map.removeLayer(previousPointGeolocation);
});
// document.getElementById('searchModal').style.display = 'block';
// End Cari Lokasi

// DIAGRAM
const diagramButton = document.getElementById('diagram');
const diagramCard = document.getElementById('diagram-card');
diagramButton.addEventListener('click', () => {
    event.stopPropagation();
    toggleDiagramCard();
});

// Toggle the card visibility
function toggleDiagramCard() {
    if (diagramCard.style.display === 'none' || diagramCard.style.display === '') {
        showDiagram(); // Call function to show the diagram
    } else {
        unshowDiagram(); // Call function to hide the diagram
    }
}

function showDiagram(){
    var diagramContent = document.getElementById('diagramContent');
    if (diagramCard.style.display === 'none' || diagramCard.style.display === '') {
        diagramCard.style.display = 'block'; // Show the card
        var diagramContent = document.getElementById('diagramContent');
    }

    // Clear existing content
    diagramContent.innerHTML = '';

    // Create section for each diagram
    function createDiagramSection(src, alt) {
        var section = document.createElement('section'); // Create section element
        section.style.marginBottom = '20px'; // Add some margin for spacing
        
        var img = document.createElement('img');
        img.src = src; // Set the image source
        img.alt = alt; // Set the alt text for accessibility
        img.style.width = '100%'; // Set width (optional)

        section.appendChild(img); // Append the image to the section
        return section; // Return the section for appending later
    }

    // Append sections to the diagram content
    diagramContent.appendChild(createDiagramSection('/pie-luas-padi.png', 'Diagram Padi'));
    diagramContent.appendChild(createDiagramSection('/pie-luas-jagung.png', 'Diagram Jagung'));
    diagramContent.appendChild(createDiagramSection('/pie-luas-kedelai.png', 'Diagram Kedelai'));
}

// Function to hide the diagram
function unshowDiagram() {
    diagramCard.style.display = 'none'; // Hide the card
    var diagramContent = document.getElementById('diagramContent');
    diagramContent.innerHTML = ''; // Clear the content when hiding
}

// Close the diagram card when clicking outside of it
document.addEventListener('click', (event) => {
    if (diagramCard.style.display === 'block' && !diagramCard.contains(event.target) && !diagramButton.contains(event.target)) {
        unshowDiagram(); // Call the unshowDiagram function
    }
});


// MEASURE
// document.getElementById('measure').addEventListener('click', addInteraction);
var measureButton = document.getElementById('measure');
measureButton.addEventListener('click', disableInfoPopup);
measureButton.addEventListener('click', () => {
    addInteraction();
    // Highlight info
    if (selectedFeature) {
        clearHighlight();
    }
    popup.getElement().style.display = 'none';
    clearSearchResults();
    
    // Search Location
    document.getElementById('searchModal').style.display = 'none'; // Menyembunyikan modal
    map.removeLayer(point_geolocation);

    document.getElementById('queryModal').style.display = 'none';
    
});

// Gaya untuk fitur yang digambar
var sourceMeasurement = new VectorSource();
var vectorLayerMeasurement = new VectorLayer({
    source: sourceMeasurement,
    style: new Style({
        fill: new Fill({
            color: 'rgba(255, 255, 255, 0.2)',
        }),
        stroke: new Stroke({
            color: 'blue',
            width: 3,
        }),
        image: new CircleStyle({
            radius: 7,
            fill: new Fill({
                color: 'blue',
            }),
        }),
    })

});
map.addLayer(vectorLayerMeasurement);

var sketch;
var helpTooltipElement;
var helpTooltip;
var measureTooltipElement;
var measureTooltip;
var continuePolygonMsg = 'Klik untuk lanjutkan gambar area'

var pointerMoveHandler = function (evt) {
    if (evt.dragging) {
        return;
    }
    /** @type {string} */
    var helpMsg = 'Mulai gambar area yang diukur';

    if (sketch) {
        const geom = sketch.getGeometry();
        if (geom instanceof Polygon) {
            helpMsg = continuePolygonMsg;
        }
    }

    if (helpTooltipElement) {
        helpTooltipElement.innerHTML = helpMsg;
        helpTooltip.setPosition(evt.coordinate);
        helpTooltipElement.classList.remove('hidden');
    }
};

map.on('pointermove', pointerMoveHandler);

map.getViewport().addEventListener('mouseout', function () {
    if (helpTooltipElement) {
        helpTooltipElement.classList.add('hidden');
    }
});

var drawMeasure;

// Format area output
var formatArea = function (polygon) {
    var area = getArea(polygon, {
        projection: 'EPSG:4326'
    });
    var output;
    if (area > 10000) {
        output = (Math.round(area / 10000 * 100) / 100) +
        ' ' + 'ha';
    } else {
        output = (Math.round(area * 100) / 100) +
            ' ' + 'm<sup>2</sup>';
    }
    return output;
};

var styleMeasurement = new Style({
    fill: new Fill({
        color: 'rgba(255, 255, 255, 0.5)'
    }),
    stroke: new Stroke({
        color: 'blue',
        lineDash: [10, 10],
        width: 2
    }),
    image: new CircleStyle({
        radius: 5,
        stroke: new Stroke({
            color: 'blue'
        }),
        fill: new Fill({
            color: 'rgba(255, 255, 255, 0.5)'
        })
    }),
});

function addInteraction() {
    if (drawMeasure) {
        map.removeInteraction(drawMeasure);
        drawMeasure = null; // Reset variabel drawMeasure
    }
    // Pastikan tooltip pengukuran sebelumnya dihapus
    if (measureTooltipElement) {
        measureTooltipElement.remove(); // Hapus elemen HTML dari DOM
    }
    if (measureTooltip) {
        map.removeOverlay(measureTooltip); // Hapus overlay dari peta
        measureTooltipElement = null;
        measureTooltip = null; // Reset variabel
    }

    drawMeasure = new Draw({
        source: sourceMeasurement,
        type: 'Polygon',
        style: function (feature) {
            const geometryType = feature.getGeometry().getType();
            if (geometryType === 'Polygon') {
                return styleMeasurement;
            }
        },
    });
    map.addInteraction(drawMeasure);

    createMeasureTooltip();
    createHelpTooltip();

    var listener;
    drawMeasure.on('drawstart', function (evt) {
        sketch = evt.feature;

        var tooltipCoord = evt.feature;

        listener = sketch.getGeometry().on('change', function (evt) {
            const geom = evt.target;
            let output;

            if (geom instanceof Polygon) {
                output = formatArea(geom);
                tooltipCoord = geom.getInteriorPoint().getCoordinates();
            }
            measureTooltipElement.innerHTML = output;
            measureTooltip.setPosition(tooltipCoord);
        });
        // sourceMeasurement.clear();
    });

    drawMeasure.on('drawend', function () {
        measureTooltipElement.className = 'ol-tooltip ol-tooltip-static';
        measureTooltip.setOffset([0, -7]);
        // unset sketch
        sketch = null;
        // unset tooltip so that a new one can be created
        measureTooltipElement = null; // Reset variabel measureTooltipElement
        createMeasureTooltip();
        unByKey(listener);
        
    });
}

function createHelpTooltip() {
    if (helpTooltipElement) {
        helpTooltipElement.remove();
    }
    helpTooltipElement = document.createElement('div');
    helpTooltipElement.className = 'ol-tooltip hidden';
    helpTooltip = new Overlay({
        element: helpTooltipElement,
        offset: [15, 0],
        positioning: 'center-left',
    });
    map.addOverlay(helpTooltip);
}

function createMeasureTooltip() {
    if (measureTooltipElement) {
        measureTooltipElement.remove();
    }
    measureTooltipElement = document.createElement('div');
    measureTooltipElement.className = 'ol-tooltip ol-tooltip-measure';
    measureTooltip = new Overlay({
        offset: [0, -15],
        positioning: 'bottom-center',

        stopEvent: false,
        insertFirst: false,
        element: measureTooltipElement,
    });
    map.addOverlay(measureTooltip);
}

// Fungsi untuk mengunduh data GeoJSON dari layer yang aktif
function downloadActiveLayerGeoJSON() {
    const layers = [
        { layer: keslahpadi, url: '/geojson/padi.geojson', fileName: 'keslah_padi.geojson' },
        { layer: keslahjagung, url: '/geojson/jagung.geojson', fileName: 'keslah_jagung.geojson' },
        { layer: keslahkedelai, url: '/geojson/kedelai.geojson', fileName: 'keslah_kedelai.geojson' }
    ];
    
    console.log("Layers:", layers);

    // Mencari semua layer yang aktif
    const activeLayers = layers.filter(l => l.layer && l.layer.getVisible());
    console.log("Active Layers:", activeLayers);

    if (activeLayers.length === 0) {
        console.error("Tidak ada layer yang aktif untuk diunduh.");
        return;
    }

    // Mengunduh data GeoJSON dari setiap layer yang aktif
    activeLayers.forEach(activeLayer => {
        fetch(activeLayer.url)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Gagal mengambil data GeoJSON dari " + activeLayer.fileName);
                }
                return response.json();
            })
            .then(data => {
                // Membuat Blob dari data JSON untuk diunduh sebagai file .geojson
                const blob = new Blob([JSON.stringify(data)], { type: 'application/json' });
                const url = URL.createObjectURL(blob);

                // Membuat link unduhan untuk setiap layer aktif
                const link = document.createElement('a');
                link.href = url;
                link.download = activeLayer.fileName; // Nama file unduhan sesuai layer
                document.body.appendChild(link);
                link.click();

                // Membersihkan URL dan link setelah unduhan
                URL.revokeObjectURL(url);
                document.body.removeChild(link);
            })
            .catch(error => {
                console.error("Error:", error);
            });
    });
}

// Menambahkan event listener pada tombol unduh
document.getElementById("unduhGeoJSON").addEventListener("click", downloadActiveLayerGeoJSON);


// Load Map
map.on('loadstart', function () {
    map.getTargetElement().classList.add('spinner');
});
map.on('loadend', function () {
    map.getTargetElement().classList.remove('spinner');
}); 