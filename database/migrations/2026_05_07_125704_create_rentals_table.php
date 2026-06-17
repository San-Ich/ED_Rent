<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('motor_id')->constrained('motors')->onDelete('cascade');
            $table->string('kode_booking')->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_rencana_kembali');
            $table->date('tanggal_pengembalian')->nullable();
            $table->integer('total_harga')->default(0);
            $table->string('payment_proof')->nullable();
            $table->integer('penalty')->default(0);
            $table->enum('status', [
                'Disewa',
                'Menunggu',
                'Selesai',
                'Gagal',
                'Batal',
                'Pending Denda',
                'Menunggu Verifikasi'
            ])->default('Menunggu');
            $table->timestamps();
            $table->string('metode_pengantaran')->default('pickup');
            $table->text('alamat_pengantaran')->nullable();
            $table->string('snap_token')->nullable();
            $table->dateTime('payment_expired_at')->nullable();
            $table->unsignedBigInteger('cabang_kembali_id')->nullable();
            $table->string('foto_serah_terima_cabang')->nullable();
            $table->string('denda_snap_token')->nullable();
            $table->dateTime('denda_expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
