<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GeoJSONData extends Model
{
    use HasFactory;
    // Tentukan tabel dan kolom yang digunakan untuk menyimpan data GeoJSON
    protected $table = 'geojson_data'; // Ganti dengan nama tabel Anda
    protected $fillable = ['name', 'geometry'];

    // Tentukan agar 'geometry' disimpan sebagai string JSON
    protected $casts = [
        'geometry' => 'array',
    ];

    // Menyimpan geometri sebagai objek geometri PostgreSQL
    public static function storeGeoJSON($geojson)
    {
        $geometry = json_encode($geojson['geometry']); // Mendapatkan geometri sebagai string JSON

        // Simpan fitur GeoJSON dalam tabel dengan konversi ke objek geometri
        DB::table('geojson_data')->insert([
            'name' => $geojson['name'] ?? 'Unnamed',
            'geometry' => DB::raw("ST_GeomFromGeoJSON('{$geometry}')") // Menggunakan PostGIS untuk konversi
        ]);
    }
}
