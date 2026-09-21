<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bms', function (Blueprint $table) {
            $table->string('qr_code')->nullable()->after('nfc_id');
        });

        // Backfill: isi qr_code dari kode_bm (hapus spasi)
        DB::table('bms')->whereNull('qr_code')->orderBy('id')->chunk(100, function ($bms) {
            foreach ($bms as $bm) {
                DB::table('bms')->where('id', $bm->id)->update([
                    'qr_code' => str_replace(' ', '', $bm->kode_bm),
                ]);
            }
        });
    }

    public function down()
    {
        Schema::table('bms', function (Blueprint $table) {
            $table->dropColumn('qr_code');
        });
    }
};

