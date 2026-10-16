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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer("num")->unique();
            $table->string("name");
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('password_email')->nullable();
            $table->integer("age")->nullable();
            $table->integer("mobile")->nullable();
            $table->integer("role_zkt")->nullable();
            $table->text("title")->nullable();

            $table->integer("friday")->nullable();
            $table->string("free_d")->nullable();
            $table->integer("sysemp")->nullable();
            $table->integer("choosesys")->nullable();
            $table->integer("check")->nullable();
            $table->string("ipz")->nullable();

            $table->unsignedBigInteger("section_id");
            $table->foreign("section_id")->references("id")->on("sections")->cascadeOnDelete();
            $table->unsignedBigInteger("shift_id");
            $table->foreign("shift_id")->references("id")->on("shifts")->cascadeOnDelete();
            $table->double("kabd", 8, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
