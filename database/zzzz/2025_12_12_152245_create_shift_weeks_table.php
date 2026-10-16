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
        Schema::create('shift_weeks', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->unsignedBigInteger("shift_id");
            $table->foreign("shift_id")->references("id")->on("shifts")->cascadeOnDelete();
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
        Schema::dropIfExists('shift_weeks');
    }
};
