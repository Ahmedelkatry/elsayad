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
        Schema::table('ipzkts', function (Blueprint $table) {
            $table->string('sn', 50)->nullable()->after('ip')->comment('Serial Number of the ZKT device (used for ADMS)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipzkts', function (Blueprint $table) {
            $table->dropColumn('sn');
        });
    }
};
