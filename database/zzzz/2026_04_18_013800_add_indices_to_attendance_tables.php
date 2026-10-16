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
        Schema::table('zkts', function (Blueprint $table) {
            $table->index(['numid', 'date']);
        });

        Schema::table('nozkts', function (Blueprint $table) {
            $table->index(['numid', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zkts', function (Blueprint $table) {
            $table->dropIndex(['numid', 'date']);
        });

        Schema::table('nozkts', function (Blueprint $table) {
            $table->dropIndex(['numid', 'date']);
        });
    }
};
