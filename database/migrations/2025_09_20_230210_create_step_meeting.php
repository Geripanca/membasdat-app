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
        Schema::create('step_meeting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pertemuan');
            $table->string('judul');
            $table->text('deskripsi');

            // Relasi opsional ke materi & quiz
            $table->unsignedBigInteger('id_materis')->nullable();
            $table->unsignedBigInteger('id_quiz')->nullable();
            $table->timestamps();

            // Relasi
            $table->foreign('id_pertemuan')->references('id')->on('meeting')->onDelete('cascade');
            $table->foreign('id_materis')->references('id')->on('materis')->onDelete('set null');
            $table->foreign('id_quiz')->references('id')->on('quizzes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('step_meeting');
    }
};
