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
        Schema::table('motors', function (Blueprint $table) {
            $table->index('status');
            $table->index('slug');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug');
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->index('status');
            $table->index('tanggal_mulai');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('motors', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['slug']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['tanggal_mulai']);
            $table->dropIndex(['created_at']);
        });
    }
};
