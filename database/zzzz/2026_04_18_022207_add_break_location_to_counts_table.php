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
            $table->decimal('latitude_break_in', 10, 8)->nullable();
            $table->decimal('longitude_break_in', 11, 8)->nullable();
            $table->decimal('latitude_break_out', 10, 8)->nullable();
            $table->decimal('longitude_break_out', 11, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('counts', function (Blueprint $table) {
            $table->dropColumn([
                'latitude_break_in', 
                'longitude_break_in', 
                'latitude_break_out', 
                'longitude_break_out'
            ]);
        });
    }
};
