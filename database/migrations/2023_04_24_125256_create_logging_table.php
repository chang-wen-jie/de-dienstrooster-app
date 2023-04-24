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
        Schema::create('logging', function (Blueprint $table) {
            $table->id('log_id');
            $table->string('uid');
            $table->string('name');
            $table->string('aa_old_status');
            $table->string('aa_new_status');
            $table->integer('duration_minutes');
            $table->timestamp('logged_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->index(['uid', 'aa_new_status', 'logged_at']);
            $table->index(['logged_at', 'aa_new_status', 'uid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logging');
    }
};
