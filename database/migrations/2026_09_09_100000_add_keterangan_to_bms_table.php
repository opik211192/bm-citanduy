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
        Schema::table('bms', function (Blueprint $table) {
            // Tambah kolom keterangan
            $table->string('keterangan')->nullable()->after('tinggi_orthometrik');

            // Hapus unique constraint lama pada kode_bm
            $table->dropUnique(['kode_bm']);

            // Buat composite unique: kode_bm + nama_pekerjaan
            $table->unique(['kode_bm', 'nama_pekerjaan'], 'bms_kode_bm_nama_pekerjaan_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bms', function (Blueprint $table) {
            $table->dropUnique('bms_kode_bm_nama_pekerjaan_unique');
            $table->unique('kode_bm');
            $table->dropColumn('keterangan');
        });
    }
};

