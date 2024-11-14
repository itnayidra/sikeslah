<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LandSuitabilityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\DataArea;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-polygon/{id}', function ($id) {
    $polygon = DataArea::find($id);  // Cari data berdasarkan ID

    if ($polygon) {
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => json_decode($polygon->geojson),  // Mengambil data GeoJSON dari kolom 'geojson'
                    'properties' => [
                        'id' => $polygon->id,
                        'nama_area' => $polygon->nama_area,
                        'deskripsi' => $polygon->deskripsi,
                    ],
                ]
            ]
        ]);
    } else {
        return response()->json(['message' => 'Polygon not found'], 404);
    }
});

Route::post('/upload-geojson', [AdminController::class, 'uploadGeoJSON']);
Route::get('/geojson/{id}', [AdminController::class, 'getGeoJSON']);
