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
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('written_by'); // Drop the 'written_by' field
            $table->string('meta_title')->nullable(); // Add 'meta_title' field
            $table->text('meta_description')->nullable(); // Add 'meta_description' field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('written_by'); // Re-add 'written_by' field
            $table->dropColumn(['meta_title', 'meta_description']); // Drop the added fields
        });
    }
};
