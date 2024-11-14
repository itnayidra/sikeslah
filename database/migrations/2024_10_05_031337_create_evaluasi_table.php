<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluasi', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id'); // Menambahkan kolom user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->id();
            $table->timestamps();
            $table->char('nama_area', 100);
            $table->char('deskripsi', 255);
            $table->polygon('area');
            $table->text('geojson')->nullable(); // Column to store GeoJSON data
            $table->double('luas');
            $table->double('temperatur');
            $table->double('bulan_basah');
            $table->enum('drainase', ['Sangat terhambat', 'Terhambat', 'Agak terhambat', 'Baik', 'Agak baik', 'Agak cepat', 'Cepat']);
            $table->enum('tekstur', ['Sangat halus', 'Halus', 'Agak halus', 'Sedang', 'Agak kasar', 'Kasar']);
            $table->double('kedalaman_tanah');
            $table->double('kejenuhan_basa');
            $table->double('ktk');
            $table->double('ph');
            $table->double('c_organik');
            $table->enum('erosi', ['Tidak ada', 'Sangat ringan', 'Ringan', 'Sedang', 'Berat', 'Sangat berat']);
            $table->enum('lereng', ['Datar', 'Agak landai', 'Landai', 'Agak curam', 'Curam', 'Sangat curam']);
            $table->enum('banjir', ['Tidak ada', 'Rendah', 'Sedang', 'Tinggi', 'Sangat tinggi']);
            $table->string('recommendation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi');
    }
};
