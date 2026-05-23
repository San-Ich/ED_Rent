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
        Schema::create('motor_specifications', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel motors
            $table->foreignId('motor_id')->constrained('motors')->onDelete('cascade');

            $table->string('kapasitas_mesin');        
            $table->string('konfigurasi_silinder');  
            $table->string('transmisi');             
            $table->string('bahan_bakar_min');       
            $table->string('sistem_pengereman');     
            $table->string('tenaga_maksimum');       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motor_specifications');
    }
};
