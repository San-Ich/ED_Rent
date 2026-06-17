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
        Schema::create('motors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('specification_id')->constrained('motor_specifications')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('brand');
            $table->string('model');
            $table->string('slug')->unique()->nullable();
            $table->string('plate_nomor')->unique();
            $table->integer('harga_per_hari');
            $table->enum('status', ['Tersedia','Perawatan', 'Disewa'])->default('Tersedia');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motors');
    }
};
