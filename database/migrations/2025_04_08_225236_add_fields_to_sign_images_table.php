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
        Schema::table('sign_images', function (Blueprint $table) {
            $table->string('white_text')->nullable();
            $table->string('yellow_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sign_images', function (Blueprint $table) {
            //
        });
    }
};
