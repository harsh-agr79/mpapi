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
        Schema::table('about_us', function (Blueprint $table) {
            $table->text('our_vision')->nullable();
            $table->string('vision_pic')->nullable();
            $table->text('mds_voice')->nullable();
            $table->string('cover_pic')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->dropColumn(['our_vision', 'vision_pic', 'mds_voice', 'cover_pic']);
        });
    }
};
