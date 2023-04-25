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
            Schema::create('rfidtoken', function (Blueprint $table) {
                $table->id();
                $table->string('uid');
                $table->string('aa_status');
                $table->string('name');
                $table->timestamps();

                $table->index('aa_status');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfidtoken');
    }
};
