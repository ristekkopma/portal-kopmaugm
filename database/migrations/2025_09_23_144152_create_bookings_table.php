<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // anggota
            $table->foreignId('book_id')->constrained()->cascadeOnDelete(); // buku
            $table->date('date_booking');
            $table->text('note')->nullable();
            $table->enum('status_booking', ['waiting', 'approve', 'rejected'])->default('waiting');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
