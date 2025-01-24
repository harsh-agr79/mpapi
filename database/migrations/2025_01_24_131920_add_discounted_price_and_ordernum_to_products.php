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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('discounted_price', 10, 2)->nullable()->after('price'); // Add discounted_price as decimal
            $table->integer('ordernum')->nullable()->after('discounted_price'); // Add ordernum as integer
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('discounted_price');
            $table->dropColumn('ordernum');
        });
    }
};
