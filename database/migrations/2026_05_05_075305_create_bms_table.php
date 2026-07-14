<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms', function (Blueprint $table) {
            $table->id();

             // DATA UTAMA
            $table->string('kode_bm')->unique(); // BMCDN01
            $table->string('nama_pekerjaan')->nullable();
            $table->string('tahun')->nullable();

            // LOKASI (TEXT DARI EXCEL)
            $table->string('provinsi')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable(); // kabupaten

            // KOORDINAT
            $table->string('utm_x')->nullable();
            $table->string('utm_y')->nullable();

            // ELEVASI
            $table->string('tinggi_orthometrik')->nullable();

            // OPTIONAL (buat nanti)
            $table->string('zone')->nullable()->default("49");
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            // FOTO (nanti dipakai saat edit)
            $table->string('foto')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms');
    }
};
