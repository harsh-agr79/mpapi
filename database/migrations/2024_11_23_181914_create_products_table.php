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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unique_id')->unique();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->json('subcategory_ids'); // To store multiple subcategories
            $table->decimal('price', 10, 2);
            $table->boolean('outofstock')->default(false);
            $table->boolean('hidden')->default(false);
            $table->text('details')->nullable();
            $table->json('specifications')->nullable(); // Key-value pairs
            $table->string('image_1')->nullable();
            $table->string('image_1_alt')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_2_alt')->nullable();
            $table->string('image_3')->nullable();
            $table->string('image_3_alt')->nullable();
            $table->string('image_4')->nullable();
            $table->string('image_4_alt')->nullable();
            $table->string('image_5')->nullable();
            $table->string('image_5_alt')->nullable();
            $table->json('colors')->nullable(); // Store multiple colors
            $table->string('sku')->nullable();
            $table->text('short_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
