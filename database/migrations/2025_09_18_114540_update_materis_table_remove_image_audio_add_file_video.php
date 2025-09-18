<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            // Hapus kolom lama
            if (Schema::hasColumn('materis', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('materis', 'audio')) {
                $table->dropColumn('audio');
            }

            // Tambah kolom baru
            $table->string('file')->nullable()->after('category');
            $table->string('video')->nullable()->after('file');
        });
    }

    public function down(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            // Rollback
            $table->string('image')->after('id');
            $table->string('audio')->nullable()->after('category');

            $table->dropColumn(['file', 'video']);
        });
    }
};
