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
        Schema::create('forget_fingers', function (Blueprint $table) {
            $table->id();
            $table->time("in")->nullable();
            $table->time("out")->nullable();
            $table->date("date")->nullable();
            $table->integer("absent")->nullable();
            $table->integer("dis_in")->nullable();
            $table->integer("dis_out")->nullable();
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
        Schema::dropIfExists('forget_fingers');
    }
};
