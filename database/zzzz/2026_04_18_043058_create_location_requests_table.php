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
        Schema::create('location_requests', function (Blueprint $table) {
            $table->id();
            $table->string('numid'); // رقم بصمة الموظف
            $table->integer('admin_id'); // معرف المدير
            $table->tinyInteger('status')->default(0); // 0: pending, 1: responded
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->timestamp('response_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_requests');
    }
};
