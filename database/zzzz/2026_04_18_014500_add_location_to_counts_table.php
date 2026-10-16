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
        Schema::table('counts', function (Blueprint $table) {
            $table->decimal('latitude_in', 10, 8)->nullable();
            $table->decimal('longitude_in', 11, 8)->nullable();
            $table->decimal('latitude_out', 10, 8)->nullable();
            $table->decimal('longitude_out', 11, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('counts', function (Blueprint $table) {
            $table->dropColumn(['latitude_in', 'longitude_in', 'latitude_out', 'longitude_out']);
        });
    }
};
