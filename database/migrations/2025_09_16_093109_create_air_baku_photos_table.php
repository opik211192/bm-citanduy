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
        Schema::create('air_baku_photos', function (Blueprint $table) {
            $table->id();
            $table->string('kode_integrasi');
            $table->string('file_path');

            $table->foreign('kode_integrasi')
                ->references('kode_integrasi')
                ->on('air_bakus')
                ->onDelete('restrict');
                
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
        Schema::dropIfExists('air_baku_photos');
    }
};
