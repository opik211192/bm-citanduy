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
            $table->string('nama_aset');
            $table->string('jenis_aset');
            $table->string('no_registrasi');
            $table->string('kode_bmn');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('village_id');
            $table->string('lat');
            $table->string('long');
            $table->string('utm_x');
            $table->string('utm_y');
            $table->string('tahun_mulai_bangunan');
            $table->string('tahun_selesai_bangunan');   
            $table->string('kondisi_bangunan');
            $table->string('keterangan');

            $table->foreign('province_id')->references('id')->on('indonesia_provinces');
            $table->foreign('city_id')->references('id')->on('indonesia_cities');
            $table->foreign('district_id')->references('id')->on('indonesia_districts');
            $table->foreign('village_id')->references('id')->on('indonesia_villages');
            
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
