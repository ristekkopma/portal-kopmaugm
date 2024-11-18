<?php

use App\Models\Member;
use App\Models\SavingCycle;
use App\Models\User;
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
        Schema::create('saving_cycle_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SavingCycle::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Member::class)->constrained()->cascadeOnDelete();
            $table->float('amount')->default(0);
            $table->timestamp('paid_off_at')->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_cycle_users');
    }
};
