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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->string('name');
            $table->string('designation');
            $table->string('profile_image'); // Store image path
            $table->unsignedTinyInteger('rating')->default(5); // 1 to 5 stars
            $table->integer('sort_order')->default(0); // For reordering
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
