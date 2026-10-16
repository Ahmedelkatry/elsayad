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
        Schema::create('company_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الفرع / الموقع
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('radius')->default(100); // النطاق بالمتر
            $table->timestamps();
        });

        // جدول الربط بين الموظف ومواقع الشركة المسموح له بالبصمة منها
        Schema::create('employee_company_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('company_location_id');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('company_location_id')->references('id')->on('company_locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_company_location');
        Schema::dropIfExists('company_locations');
    }
};
