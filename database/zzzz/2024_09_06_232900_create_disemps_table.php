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
        Schema::create('disemps', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->double("dis")->nullable();
            $table->date("date")->nullable();
            $table->text("dec")->nullable();
            $table->unsignedBigInteger("employees_id");
            $table->foreign("employees_id")->references("id")->on("employees")->cascadeOnDelete();

            $table->unsignedBigInteger("section_id");
            $table->foreign("section_id")->references("id")->on("sections")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disemps');
    }
};
