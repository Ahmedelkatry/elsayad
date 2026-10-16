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
        Schema::table('tlbpleases', function (Blueprint $table) {
            $table->date('dateshift_end')->nullable()->after('dateshift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tlbpleases', function (Blueprint $table) {
            //
        });
    }
};
