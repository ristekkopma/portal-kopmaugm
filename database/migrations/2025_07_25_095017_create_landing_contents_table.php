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
    Schema::create('landing_static_contents', function (Blueprint $table) {
    $table->id();
    $table->string('hero_title')->nullable();
    $table->text('hero_paragraph')->nullable();
    $table->string('hero_image')->nullable();

    $table->string('tata_cara_1')->nullable();
    $table->string('tata_cara_2')->nullable();
    $table->string('tata_cara_3')->nullable();

    $table->text('benefit_1_text')->nullable();
    $table->string('benefit_1_image')->nullable();
    
    $table->text('footer_address')->nullable();
    $table->string('footer_maps')->nullable();
    $table->string('footer_email')->nullable();
    $table->string('footer_instagram')->nullable();
    
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
