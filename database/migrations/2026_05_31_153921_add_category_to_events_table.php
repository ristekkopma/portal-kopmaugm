<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kategori event ke tabel events.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('category', [
                'urgent',
                'bulanan',
                'tahunan',
            ])->default('bulanan')->after('url');
        });
    }

    /**
     * Menghapus kategori event jika migration dibatalkan.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};