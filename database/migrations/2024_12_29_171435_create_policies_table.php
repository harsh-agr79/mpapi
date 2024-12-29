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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Privacy Policy');
            $table->longText('content')->nullable();
            $table->timestamps();
        });

        // Seed an initial record
        DB::table('policies')->insert([
            'title' => 'Privacy Policy',
            'content' => 'Your policy content goes here...',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
