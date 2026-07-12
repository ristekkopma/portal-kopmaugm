<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan deleted_at hanya jika kolomnya belum tersedia.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('events', 'deleted_at')) {
            Schema::table('events', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Hapus deleted_at jika migration dibatalkan.
     */
    public function down(): void
    {
        if (Schema::hasColumn('events', 'deleted_at')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};