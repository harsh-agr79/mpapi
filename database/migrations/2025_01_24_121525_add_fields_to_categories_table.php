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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon_image')->nullable()->after('name'); // Field for category icon image
            $table->boolean('show_in_homepage')->default(false)->after('icon_image'); // Boolean field
            $table->string('short_description', 255)->nullable()->after('show_in_homepage'); // Short text description
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
};
