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
        Schema::create('geojson_data', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->geometry('geometry', 4326); // Pastikan PostGIS terinstall untuk tipe data geometry
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geojson_data');
    }
};
