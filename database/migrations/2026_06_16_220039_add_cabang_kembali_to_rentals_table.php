<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->unsignedBigInteger('cabang_kembali_id')->nullable()->after('status');
            $table->string('foto_serah_terima_cabang')->nullable()->after('cabang_kembali_id');

        });
    }

    public function down()
    {
        Schema::table('rentalss', function (Blueprint $table) {
            $table->dropColumn(['cabang_kembali_id', 'foto_serah_terima_cabang']);
        });
    }
};
