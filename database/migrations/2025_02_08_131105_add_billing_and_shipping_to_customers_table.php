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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('billing_full_name')->nullable();
            $table->string('billing_phone_number')->nullable();
            $table->string('billing_country_region')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_postal_code')->nullable();

            // Shipping Address Fields
            $table->string('shipping_full_name')->nullable();
            $table->string('shipping_phone_number')->nullable();
            $table->string('shipping_country_region')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_postal_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
