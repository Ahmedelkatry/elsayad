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
        Schema::create('pleases', function (Blueprint $table) {
            $table->id();
            $table->date("date")->nullable();
            $table->integer("in_out")->nullable();
            $table->integer("dis_value")->nullable();
            $table->integer("dis")->nullable();
            $table->integer("minutes")->nullable();
            $table->text("dec")->nullable();
             $table->unsignedBigInteger("employees_id");
             $table->foreign("employees_id")->references("id")->on("employees")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pleases');
    }
};
