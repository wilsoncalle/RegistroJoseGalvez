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
        Schema::create('backup_schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('frequency', ['daily', 'weekly', 'monthly'])->default('weekly');
            $table->integer('day_of_week')->nullable(); // 0 = domingo, 6 = sábado
            $table->integer('day_of_month')->nullable(); // 1-31
            $table->string('time')->default('00:00'); // Formato HH:MM
            $table->integer('retention_count')->default(5);
            $table->boolean('auto_backup')->default(false);
            $table->timestamp('last_run')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_schedules');
    }
};
