<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_followers', function (Blueprint $table) {
            $table->enum('status', ['interested', 'registered', 'attended', 'cancelled'])
                ->default('interested')
                ->after('user_id');
            $table->text('notes')->nullable()->after('status');
            $table->softDeletes();
        });

        DB::table('event_followers')->whereNull('status')->update(['status' => 'interested']);
    }

    public function down(): void
    {
        Schema::table('event_followers', function (Blueprint $table) {
            $table->dropColumn(['status', 'notes', 'deleted_at']);
        });
    }
};
