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
        Schema::table('benchmarks', function (Blueprint $table) {
            $table->unsignedBigInteger('konsultan_id')
                  ->nullable()
                  ->after('keterangan');

            $table->foreign('konsultan_id')
                  ->references('id')
                  ->on('konsultans')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('benchmarks', function (Blueprint $table) {
             $table->dropForeign(['konsultan_id']);
            $table->dropColumn('konsultan_id');
        });
    }
};
