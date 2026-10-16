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
        Schema::create('countips', function (Blueprint $table) {

            $table->id();
            $table->integer("numid")->nullable();
            $table->string("name")->nullable();
            $table->string("shift")->nullable();
            $table->time("in_duty")->nullable();
            $table->time("out_duty")->nullable();
            $table->date("date")->nullable();
            $table->integer("absent")->nullable();
            $table->integer("friday")->nullable();
            $table->time("in")->nullable();
            $table->time("out")->nullable();
            $table->time("ot")->nullable();
            $table->double("othours")->nullable();
            $table->integer("dayh")->nullable();
            $table->time("early")->nullable();
            $table->double("dis")->nullable();
            $table->double("disout")->nullable();
            $table->double("kabdd")->nullable();
            $table->double("kabddis")->nullable();

            // $table->unsignedBigInteger("shift_id");
            // $table->foreign("shift_id")->references("id")->on("shifts")->cascadeOnDelete();
            // $table->unsignedBigInteger("section_id");
            // $table->foreign("section_id")->references("id")->on("sections")->cascadeOnDelete();
            // $table->unsignedBigInteger("employees_id");
            // $table->foreign("employees_id")->references("id")->on("employees")->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countips');
    }
};
