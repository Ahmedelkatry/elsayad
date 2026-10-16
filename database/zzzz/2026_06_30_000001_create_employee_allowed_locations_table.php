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
        Schema::create('employee_allowed_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('name'); // اسم المكان مثل: المقر الرئيسي - فرع المعادي
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('radius')->default(100); // نطاق القبول بالمتر
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_allowed_locations');
    }
};
