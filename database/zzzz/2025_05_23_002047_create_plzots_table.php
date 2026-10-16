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
        Schema::create('plzots', function (Blueprint $table) {
            $table->id();
            $table->integer("numid")->nullable();
            $table->string("name")->nullable();
            $table->time("in_duty")->nullable();
            $table->time("out_duty")->nullable();
            $table->date("date")->nullable();
              $table->time("in")->nullable();
            $table->time("out")->nullable();
             $table->time("ot")->nullable();
            $table->double("othours")->nullable();
            $table->decimal("count_ot")->nullable();
            $table->integer("status")->nullable();

             $table->decimal('latitude_in', 10, 8)->nullable();
            $table->decimal('longitude_in', 11, 8)->nullable();
            $table->decimal('latitude_out', 10, 8)->nullable();
            $table->decimal('longitude_out', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plzots');
    }
};
