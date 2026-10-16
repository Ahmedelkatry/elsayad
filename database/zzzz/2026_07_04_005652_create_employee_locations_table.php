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
        // نستخدم ifNotExists لتجنب الأخطاء إذا كانت الجداول موجودة مسبقاً
        if (!Schema::hasTable('employee_locations')) {
            Schema::create('employee_locations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employees_id');
                $table->decimal('latitude', 10, 8);
                $table->decimal('longitude', 11, 8);
                $table->timestamp('recorded_at')->nullable();
                $table->timestamps();

                $table->foreign('employees_id')->references('id')->on('employees')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_locations');
    }
};
