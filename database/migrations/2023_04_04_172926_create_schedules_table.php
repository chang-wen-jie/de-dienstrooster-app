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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('employees');
            $table->enum('weekday', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->enum('week_type', ['odd', 'even']);
            $table->time('shift_start_time');
            $table->time('shift_end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
