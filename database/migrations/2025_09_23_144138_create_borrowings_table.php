<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('book_id')->constrained()->onDelete('cascade');
    $table->date('date_borrowing');
    $table->date('date_return')->nullable();
    $table->enum('status', ['borrowed', 'returned', 'late'])->default('borrowed');
    $table->decimal('penalty_charge', 10, 2)->default(0); // biaya keterlambatan
    $table->timestamps();
    
});
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
