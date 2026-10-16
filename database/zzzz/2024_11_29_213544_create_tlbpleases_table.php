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
        Schema::create('tlbpleases', function (Blueprint $table) {
            $table->id();
            $table->integer("num")->nullable();
            $table->date("date")->nullable();
            $table->date("datesalf")->nullable();
            $table->date("dateshift")->nullable();
            $table->date("dateplz")->nullable();
            $table->integer("in_out")->nullable();
            $table->integer("min")->nullable();
            $table->double("dis")->nullable();
            $table->double("dis_value")->nullable();
            $table->text("textfree")->nullable();
            $table->text("textshift")->nullable();
            $table->text("textsalf")->nullable();
            $table->text("textplz")->nullable();
            $table->integer("salf")->nullable();
            $table->string("name")->nullable();
            $table->integer("status")->nullable();
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
        Schema::dropIfExists('tlbpleases');
    }
};
