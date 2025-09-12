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
       Schema::create('aset_photos', function (Blueprint $table) {
            $table->id();
            $table->string('kode_integrasi');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('kode_integrasi')
                ->references('kode_integrasi')
                ->on('asets')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aset_photos');
    }
};
