import 'ol/ol.css';
// import 'ol-layerswitcher/dist/ol-layerswitcher.css';
// import 'ol-popup/dist/ol-popup.css';
import Map from 'ol/Map.js';
import OSM from 'ol/source/OSM.js';
import XYZ from 'ol/source/XYZ.js';
import TileLayer from 'ol/layer/Tile.js';
import Group from 'ol/layer/Group.js';
import View from 'ol/View.js';
import ScaleLine from 'ol/control/ScaleLine.js';
import LayerSwitcher from 'ol-layerswitcher';
import GeoJSON from 'ol/format/GeoJSON.js';
import Draw from 'ol/interaction/Draw.js';
import { Polygon, Point } from 'ol/geom';
import {Circle as CircleStyle, Fill, Stroke, Style, Icon, RegularShape, Text} from 'ol/style.js';
import {Vector as VectorSource, Cluster} from 'ol/source.js';
import {Vector as VectorLayer} from 'ol/layer.js';
import { getArea } from 'ol/sphere.js';
import Overlay from 'ol/Overlay.js';
import { unByKey } from 'ol/Observable';


var view = new View({
    projection: 'EPSG:4326',
    center: [110.38399799809093, -7.7043285625487075],
    zoom: 11.5,
});

// Inisialisasi peta dan layer dasar
const map = new Map({
    target: 'map', // ID elemen HTML di mana peta akan dirender
    layers: [
        new TileLayer({
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
    controls: [],
    view: view,
});

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

var overlays = new Group({
    'title': 'Layer',
    layers: []
});
var overlay_PL = new Group({
    'title': 'Jenis Sawah',
    layers: []
})
var overlay_admin = new Group({
    'title': 'Batas Administrasi',
    layers: []
})


// Layer Switcher
var layerSwitcher = new LayerSwitcher({
    activationMode: 'click',
    tipLabel: 'Tampilkan peta', // Optional label for button
    collapseTipLabel: 'Sembunyikan peta', // Optional label for button
    groupSelectStyle: 'children' // Can be 'children' [default], 'group' or 'none'
});
map.addControl(layerSwitcher);
map.addLayer(base_maps);
map.addLayer(overlays);
overlays.getLayers().push(overlay_PL);
overlays.getLayers().push(overlay_admin);


// Define the styles based on the 'Keterangan' property
const styleAdmin = function (feature) {
    return new Style({
        stroke: new Stroke({
            color: 'black',
            width: 1,
        }),
        zIndex: 10,
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

// Sumber vektor untuk data GeoJSON kapanewon
const kapanewonSource = new VectorSource({
    url: '/geojson/adminkap.geojson', // Ganti dengan path ke file GeoJSON Anda
    format: new GeoJSON(),
});
// Layer vektor untuk kapanewon
const adminkec = new VectorLayer({
    source: kapanewonSource,
    title: 'Batas Kapanewon',
    style: styleAdmin,
});
// map.addLayer(adminkec);

// Membuat pola diagonal dengan Canvas
function createDiagonalPattern() {
    var canvas = document.createElement('canvas');
    canvas.width = 10;
    canvas.height = 10;
    var context = canvas.getContext('2d');

    // Membuat garis diagonal
    context.strokeStyle = '#4CAF50'; // Warna hijau terang
    context.lineWidth = 2;
    context.beginPath();
    context.moveTo(0, 10);
    context.lineTo(10, 0);
    context.stroke();

    return context.createPattern(canvas, 'repeat'); // Pola berulang
}

// Style untuk sawah irigasi dengan pattern diagonal
var irigasiStyle = new Style({
    fill: new Fill({
        color: createDiagonalPattern() // Menggunakan pattern sebagai fill
    }),
    zIndex: 5,
    stroke: new Stroke({
        color: '#4CAF50', // Tepi poligon dengan warna hijau terang
        width: 1
    })
});

// Membuat pola titik-titik dengan Canvas
function createDotPattern() {
    var canvas = document.createElement('canvas');
    canvas.width = 10;
    canvas.height = 10;
    var context = canvas.getContext('2d');

    // Membuat titik di tengah
    context.fillStyle = '#FFD700'; // Warna kuning
    context.beginPath();
    context.arc(5, 5, 2, 0, 2 * Math.PI, false);
    context.fill();

    return context.createPattern(canvas, 'repeat'); // Pola berulang
}

// Style untuk sawah tadah hujan dengan pattern titik-titik
var tadahHujanStyle = new Style({
    fill: new Fill({
        color: createDotPattern() // Menggunakan pattern sebagai fill
    }),
    zIndex: 5,
    stroke: new Stroke({
        color: '#FFD700', // Tepi poligon dengan warna kuning
        width: 1
    })
});

// Add the GeoJSON layer to the map with the style function
// Sumber vektor untuk data GeoJSON kapanewon
const PLSawahIrigasiSource = new VectorSource({
    url: '/geojson/sawahirigasi.geojson', // Ganti dengan path ke file GeoJSON Anda
    format: new GeoJSON(),
});

// Layer vektor untuk kapanewon
const PLSawahIrigasiLayer = new VectorLayer({
    source: PLSawahIrigasiSource,
    title: 'Sawah Irigasi',
    style: irigasiStyle,
});

const PLSawahTadahHujanSource = new VectorSource({
    url: '/geojson/sawahtadahhujan.geojson', // Ganti dengan path ke file GeoJSON Anda
    format: new GeoJSON(),
});
// Layer vektor untuk kapanewon
const PLSawahTadahHujanLayer = new VectorLayer({
    source: PLSawahTadahHujanSource,
    title: 'Sawah Tadah Hujan',
    style: tadahHujanStyle,
});


// // Add the layer to the map
// map.addLayer(keslahPadiLayer);
overlay_PL.getLayers().push(PLSawahIrigasiLayer);
overlay_PL.getLayers().push(PLSawahTadahHujanLayer);

// // Add the layer to the map
// map.addLayer(keslahPadiLayer);
overlay_admin.getLayers().push(adminkec);
// overlay_admin.getLayers().push(adminkab);

// // MOUSE POINTER COORDINATE
// var mouse_position = new MousePosition();
// map.addControl(mouse_position);

// Fungsi untuk mengatur ulang peta ke posisi awal
function resetMap() {
    map.getView().animate({
        center: [110.38399799809093, -7.7043285625487075],
        zoom: 11.5,
        duration: 1000 // Durasi animasi dalam milidetik
    });
}

// PUSATKAN PETA
document.getElementById('base').addEventListener('click', resetMap);

// ZOOM IN
document.getElementById('zoomin').addEventListener('click', function() {
    const view = map.getView();
    const zoom = view.getZoom();
    view.setZoom(zoom + 1);
});

// ZOOM OUT
document.getElementById('zoomout').addEventListener('click', function() {
    const view = map.getView();
    const zoom = view.getZoom();
    view.setZoom(zoom - 1);
});

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

let currentIcons = {}; // Menyimpan ikon yang digunakan untuk hasil pencarian
var isSearchActive = false; // Status apakah pencarian aktif atau tidak

// LEGENDA
function updateLegend() {
    var legendContent = document.getElementById('legendContent');
    legendContent.innerHTML = ''; // Hapus konten legenda sebelumnya

     // Jika pencarian aktif, tampilkan hanya ikon hasil pencarian
     if (isSearchActive) {
        Object.keys(currentIcons).forEach(function(key) {
            var iconUrl = currentIcons[key];
            var label = document.createElement('div');
            label.innerHTML = '<strong>' + key + '</strong>'; // Label untuk ikon
            legendContent.appendChild(label);

            var img = document.createElement('img');
            img.src = iconUrl;
            legendContent.appendChild(img);
        });
    } else {
        // Fungsi untuk memperbarui legenda berdasarkan layer dari grup tertentu
        function updateLegendForGroup(group) {

            var sawahIcons = []; // Array untuk menyimpan ikon jenis sawah
            var adminLegendUrl = ''; // URL untuk legenda batas administrasi

            group.getLayers().forEach(function(layer) {
                if (layer.getVisible() && layer.get('title')) {
                    // Tentukan URL legenda berdasarkan judul layer
                    if (layer.get('title') === 'Batas Administrasi') {
                        adminLegendUrl = '/icon/legend_admin.png'; // URL untuk legenda Batas Administrasi
                    } else if (layer.get('title') === 'Sawah Irigasi') {
                        sawahIcons.push('/icon/legend_sawah_irigasi.png'); // Tambahkan ikon untuk Sawah Irigasi
                    } else if (layer.get('title') === 'Sawah Tadah Hujan') {
                        sawahIcons.push('/icon/legend_sawahtadah.png'); // Tambahkan ikon untuk Sawah Tadah Hujan
                    }
                }
            });

            // Tampilkan simbol untuk Batas Administrasi
            if (adminLegendUrl) {
                var adminLabel = document.createElement('div');
                adminLabel.innerHTML = '<strong>Batas Administrasi</strong>';
                legendContent.appendChild(adminLabel);
                
                var adminImg = document.createElement('img');
                adminImg.src = adminLegendUrl;
                legendContent.appendChild(adminImg);
            }

            // Tampilkan simbol untuk jenis sawah jika ada
            if (sawahIcons.length > 0) {
                var sawahLabel = document.createElement('div');
                sawahLabel.innerHTML = '<strong>Jenis Sawah</strong>'; // Label umum untuk jenis sawah
                legendContent.appendChild(sawahLabel);

                sawahIcons.forEach(function(icon) {
                    var img = document.createElement('img');
                    img.src = icon;
                    legendContent.appendChild(img);
                });

                // var img = document.createElement('img');
                // img.src = legendUrl;
                // legendContent.appendChild(img);
            }

                    
                    // // Jika grup adalah 'Jenis Sawah', tampilkan label
                    // if (layer.get('title') === 'Jenis Sawah') {
                    //     var sawahLabel = document.createElement('div');
                    //     sawahLabel.innerHTML = '<strong>Jenis Sawah</strong>';
                    //     legendContent.appendChild(sawahLabel);
                    // }

                    // Tambahkan gambar legenda
            
            
            
        }
        // Perbarui legenda untuk masing-masing grup
        updateLegendForGroup(overlays);
        updateLegendForGroup(overlay_PL);
    }
}

// Panggil updateLegend untuk pertama kali saat halaman dimuat
updateLegend();

overlays.getLayers().forEach(function(layer) {
    layer.on('change:visible', function() {
        updateLegend(); // Perbarui legenda setiap kali visibilitas layer berubah
    });
});

// Tambahkan listener untuk memperbarui legenda ketika visibilitas layer berubah pada overlay_PL
overlay_PL.getLayers().forEach(function(layer) {
    layer.on('change:visible', function() {
        updateLegend();
    });
});

// Variable to track the active state of the info popup
let isInfoPopupActive = false;

// Function to enable info popup
function enableInfoPopup() {
    isInfoPopupActive = true;
}

// Function to disable info popup
function disableInfoPopup() {
    isInfoPopupActive = false;
    var container = document.getElementById('popup');
    var overlay = map.getOverlays().getArray().find(function(o) {
        return o.getElement() === container;
    });

    if (overlay) {
        // Sembunyikan popup
        overlay.setPosition(undefined);
        container.style.display = 'none';
    }
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
// Button event listeners
var infoButton = document.getElementById('info');
infoButton.addEventListener('click', () => { 
    enableInfoPopup();
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
    // Event saat peta diklik
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
        map.getView().setZoom(18); 
    });
});

// Inisialisasi sumber data polygon 
var vectorInfoPopupSource = new VectorSource({
    format: new GeoJSON(),
    url: '/geojson/sawahkeslah.geojson', 
});

// Inisialisasi layer untuk polygon
var vectorInfoPopupLayer = new VectorLayer({
    source: vectorInfoPopupSource,
    style: new Style({
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
    if (content) {
        content.innerHTML = '<p>Data ditemukan!</p>';
    } else {
        console.error("Elemen dengan id 'popup-content' tidak ditemukan.");
    }
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
            <strong style="font-family: Nunito;">Informasi Lahan Sawah</strong>
            <table style="width:100%; border-collapse: collapse; margin-top: 10px; font-family: Nunito;">
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Kapanewon</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">${data.get('kapanewon')}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Jenis Lahan</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">${data.get('pl')}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Luas</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">${formatluas} m<sup>2</sup></td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Daerah Irigasi</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">${data.get('di')}</td>
                </tr>
            </table>

            <strong style="display: block; margin-top: 10px; font-family: Nunito;">Tanaman yang Sesuai</strong>
            <table style="width:100%; border-collapse: collapse; font-family: Nunito;">
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">${data.get('tanaman')}</td>
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
    document.getElementById('popup-closer').onclick = function() {
        overlay.setPosition(undefined);
        // Hilangkan highlight ketika popup ditutup
        clearHighlight();
        return false;        
    };
}

var kursorButton = document.getElementById('cursor');
kursorButton.addEventListener('click', disableInfoPopup);
kursorButton.addEventListener('click', () => {
    clearHighlight();
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
});


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

    // Create an <img> element
    var img = document.createElement('img');
    img.src = '/pie-diagram.png'; // Set the image source
    img.alt = 'Diagram Image'; // Optional: Set the alt text for accessibility

    // Append the image to the diagram content
    diagramContent.appendChild(img);
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
    clearHighlight();
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
    }});

var drawMeasure;

// Format area output
var formatArea = function(polygon){
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

// UNDUH DATA
// Fungsi untuk mengunduh data GeoJSON dari layer yang aktif
function downloadActiveLayerGeoJSON() {
    const layers = [
        { layer: PLSawahIrigasiLayer, url: '/geojson/sawahirigasi.geojson', fileName: 'data_s_irigasi.geojson' },
        { layer: PLSawahIrigasiLayer, url: '/geojson/sawahtadahhujan.geojson', fileName: 'data_s_t_hujan.geojson' },
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

map.on('loadstart', function () {
    map.getTargetElement().classList.add('spinner');
  });
  map.on('loadend', function () {
    map.getTargetElement().classList.remove('spinner');
  }); 