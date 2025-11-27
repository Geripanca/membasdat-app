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
        Schema::create('pivot_meeting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('step_meeting_id');
            $table->unsignedBigInteger('materi_id');
            $table->timestamps();

            // Relasi dengan onDelete cascade
            $table->foreign('step_meeting_id')->references('id')->on('step_meeting')->onDelete('cascade');
            $table->foreign('materi_id')->references('id')->on('materis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_meeting');
    }
};
