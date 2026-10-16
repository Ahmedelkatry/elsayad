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
        Schema::create('zkts', function (Blueprint $table) {
            $table->id();
            $table->integer("numid")->nullable();
            $table->string("name")->nullable();
            $table->string("absent")->nullable();
            $table->time("in")->nullable();
            $table->time("out")->nullable();
            $table->time("break_in")->nullable();
            $table->time("break_out")->nullable();
            $table->date("date")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zkts');
    }
};
