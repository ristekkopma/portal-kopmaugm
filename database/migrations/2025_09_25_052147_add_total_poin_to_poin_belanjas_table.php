<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('poin_belanjas', function (Blueprint $table) {
        $table->unsignedInteger('total_poin')->default(0)->after('tanggal_transaksi');
    });
}

public function down(): void
{
    Schema::table('poin_belanjas', function (Blueprint $table) {
        $table->dropColumn('total_poin');
    });
}

};
