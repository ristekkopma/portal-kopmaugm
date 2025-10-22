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
        Schema::create('announcement_recipients', function (Blueprint $table) {
    $table->id();
    $table->foreignId('announcement_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    $table->string('whatsapp')->nullable();
    $table->enum('status', ['pending','sent','failed'])->default('pending');
    $table->timestamp('sent_at')->nullable();

    $table->unsignedInteger('attempts')->default(0);
    $table->text('last_error')->nullable();
    $table->string('waha_message_id')->nullable(); // jika WAHA mengembalikan ID
    $table->json('waha_raw')->nullable();

    $table->timestamps();
    $table->unique(['announcement_id','user_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_recipients');
    }
};
