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
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_integrasi')->unique()->nullable();
            $table->string('kode_bmn');
            $table->string('nama_aset');
            $table->string('jenis_aset');
            $table->string('wilayah_sungai');
            $table->string('das');

            // Lokasi langsung string
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('village')->nullable();

            // Koordinat
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('utm_x')->nullable();
            $table->string('utm_y')->nullable();

            // Informasi bangunan
            $table->string('tahun_mulai_bangunan')->nullable();
            $table->string('tahun_selesai_bangunan')->nullable();
            $table->string('kondisi_bangunan')->nullable();
            $table->string('status_operasi')->nullable();
            $table->text('kondisi_infrastruktur')->nullable();

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
        Schema::dropIfExists('asets');
    }
};
