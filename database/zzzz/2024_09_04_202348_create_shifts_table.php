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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->time("from")->nullable();
            $table->time("to")->nullable();
            $table->integer("shift_time")->nullable();
            $table->double("late")->nullable();
            $table->double("late1")->nullable();
            $table->double("late2")->nullable();
            $table->double("late3")->nullable();
            $table->double("bdry")->nullable();
            $table->double("workh")->nullable();
            $table->double("dism")->nullable();
            $table->double("dism1")->nullable();
            $table->double("dism2")->nullable();
            $table->double("dism3")->nullable();
            $table->double("disout")->nullable();
            $table->time("break_in")->nullable();
            $table->time("break_out")->nullable();
            $table->double("b_out_dis")->nullable();
            $table->double("b_in_dis")->nullable();
            $table->double("absent")->nullable();
            // $table->integer("friday")->nullable();
            // $table->string("free_d")->nullable();
            // $table->integer("sysemp")->nullable();
            $table->time("start_ot")->nullable();
            $table->double("ot")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
