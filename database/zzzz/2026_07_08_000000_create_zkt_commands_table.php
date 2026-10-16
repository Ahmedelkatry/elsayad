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
        Schema::create('zkt_commands', function (Blueprint $table) {
            $table->id();
            $table->string('sn'); // سيريال الجهاز اللي هيبعتله الأمر
            $table->text('command'); // الأمر نفسه (إضافة موظف)
            $table->boolean('executed')->default(0); // 0 = لسه متنفذش، 1 = تم التنفيذ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zkt_commands');
    }
};
