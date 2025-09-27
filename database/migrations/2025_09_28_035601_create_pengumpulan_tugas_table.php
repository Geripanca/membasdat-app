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
    Schema::create('pengumpulan_tugas', function (Blueprint $table) {
        $table->id('id_pengumpulan');
        $table->unsignedBigInteger('id_tugas');
        $table->unsignedBigInteger('id_siswa');
        $table->string('file')->nullable();
        $table->text('keterangan')->nullable();
        $table->enum('status', ['menunggu', 'dikumpulkan', 'terlambat','dinilai'])->default('menunggu');
        $table->decimal('nilai', 5, 2)->nullable(); // contoh: 100.00 max
        $table->dateTime('submit_at')->nullable();
        $table->timestamps();

        $table->foreign('id_tugas')->references('id_tugas')->on('tugas')->onDelete('cascade');
        $table->foreign('id_siswa')->references('id')->on('users')->onDelete('cascade');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
