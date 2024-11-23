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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Default ID
            $table->string('name'); // Category Name
            $table->string('meta_title')->nullable(); // Meta Title
            $table->text('meta_description')->nullable(); // Meta Description
            $table->string('image')->nullable(); // Path to Image
            $table->string('imagefiletag')->nullable(); // Image File Tag
            $table->string('alttext')->nullable(); // Alt Text for Image
            $table->timestamps(); // Created at, Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
