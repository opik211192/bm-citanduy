<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bm_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bm_id');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('bm_id')
                ->references('id')
                ->on('bms')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bm_photos');
    }
};

