<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataArea extends Model
{
    use HasFactory;

    protected $table = 'evaluasi';

    protected $fillable = [
        'user_id',
        'nama_area',
        'deskripsi',
        'area',
        'geojson',
        'luas',
        'temperatur',
        'bulan_basah',
        'drainase',
        'tekstur',
        'kedalaman_tanah',
        'kejenuhan_basa',
        'ktk',
        'ph',
        'c_organik',
        'erosi',
        'lereng',
        'banjir',
        'recommendation',
    ];

    protected $dates = ['created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
