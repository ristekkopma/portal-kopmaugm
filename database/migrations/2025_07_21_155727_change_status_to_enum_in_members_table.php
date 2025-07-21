<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusToEnumInMembersTable extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('status')->change(); // atau ->integer()->change(); jika sebelumnya integer
        });
    }
}
