<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title_book');
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->year('year_publish')->nullable();
            $table->string('isbn')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('stock')->default(0);
            $table->enum('status', ['available', 'no available'])->default('available'); // otomatis
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
