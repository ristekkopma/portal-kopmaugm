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
        Schema::create('announcements', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('message');
    $table->boolean('is_broadcast')->default(false);
    $table->json('target_user_ids')->nullable(); // simpan array id user jika non-broadcast
    $table->enum('status', ['draft','queued','sending','sent','failed'])->default('draft');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
