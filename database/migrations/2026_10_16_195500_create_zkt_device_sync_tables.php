<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('zkt_device_sync')) {
            Schema::create('zkt_device_sync', function (Blueprint $table) {
                $table->id();
                $table->string('device_key', 191);
                $table->string('emp_num', 191);
                $table->timestamp('synced_at')->nullable();
                $table->timestamps();

                $table->unique(['device_key', 'emp_num']);
            });
        }

        if (!Schema::hasTable('zkt_device_deletions')) {
            Schema::create('zkt_device_deletions', function (Blueprint $table) {
                $table->id();
                $table->string('device_key', 191);
                $table->string('emp_num', 191);
                $table->timestamp('deleted_at')->nullable();
                $table->timestamps();

                $table->unique(['device_key', 'emp_num']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('zkt_device_sync');
        Schema::dropIfExists('zkt_device_deletions');
    }
};
