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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->date("date_end")->nullable();
            $table->date("date_start")->nullable();
            $table->integer("mony")->nullable();
            $table->integer("mony_now")->nullable();
            $table->integer("mony_after")->nullable();
            $table->integer("month")->nullable();
            $table->integer("month_now")->nullable();

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
        Schema::dropIfExists('loans');
    }
};
