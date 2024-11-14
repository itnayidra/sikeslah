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
        Schema::create('dataarea', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->char('nama_area', 100);
            $table->multiPolygon('area');
            $table->double('luas');
            $table->double('temperatur');
            $table->double('bulan_basah');
            $table->enum('drainase', ['sangat_terhambat', 'terhambat', 'agak_terhambat', 'baik', 'agak_cepat', 'cepat']);
            $table->enum('tekstur', ['sangat_halus', 'halus', 'agak_halus', 'sedang', 'agak_kasar', 'kasar']);
            $table->enum('kedalaman_tanah', ['sangat_dangkal(<50)', 'dangkal(50-70)', 'sedang(>75-100)', 'dalam(>100)']);
            $table->enum('kejenuhan_basa', ['tanah_sangatsubur(>80)', 'tanah_subursedang(>50-80)', 'tanah_suburrendah(<50)']);
            $table->double('ktk');
            $table->double('ph');
            $table->double('c_organik');
            $table->enum('erosi', ['sangat_ringan', 'ringan', 'sedang', 'berat', 'sangat_berat']);
            $table->enum('lereng', ['datar(<8)', 'landai(>8-15)', 'agak_curam(>15-25)', 'curam(>25-45)', 'sangat_curam(>45)']);
            $table->enum('banjir', ['tidakada', 'ringan', 'sedang', 'agak_berat', 'berat']);
            $table->string('recommendation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataarea');
    }
};
