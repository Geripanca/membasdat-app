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
        Schema::table('step_meeting', function (Blueprint $table) {
            $table->dropForeign(['id_materis']); // kalau ada foreign key
            $table->dropColumn('id_materis');
    });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('step_meeting', function (Blueprint $table) {
            $table->unsignedBigInteger('id_materis')->nullable();
            $table->foreign('id_materis')->references('id')->on('materis')->onDelete('set null');
        });
    }
};
