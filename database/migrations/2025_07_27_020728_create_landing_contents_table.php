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
    Schema::create('landing_contents', function (Blueprint $table) {
        $table->id();
        $table->string('hero_title')->nullable();
        $table->text('hero_paragraph')->nullable();
        $table->string('hero_image')->nullable();
        $table->text('intro_text')->nullable();
        $table->string('step_image_1')->nullable();
        $table->string('step_image_2')->nullable();
        $table->string('step_image_3')->nullable();
        $table->text('alamat')->nullable();
        $table->text('map_iframe')->nullable();
        $table->string('instagram')->nullable();
        $table->string('youtube')->nullable();
        $table->string('twitter')->nullable();
        $table->string('linkedin')->nullable();
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_contents');
    }
};
