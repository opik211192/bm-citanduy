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
            // Kolom NFC ID untuk scan NFC tag
            $table->string('nfc_id')->nullable()->unique()->after('keterangan');
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
            $table->dropColumn('nfc_id');
        });
    }
};

