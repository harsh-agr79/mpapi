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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // order_id
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('order_date')->useCurrent();

            // Billing Details
            $table->string('billing_full_name');
            $table->string('billing_phone_number');
            $table->string('billing_country_region');
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_email');
            $table->string('billing_postal_code');
            $table->string('billing_street_address');
            $table->string('billing_municipality')->nullable();
            $table->text('billing_ordernote')->nullable();

            // Shipping Details
            $table->string('shipping_full_name');
            $table->string('shipping_phone_number');
            $table->string('shipping_country_region');
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_email');
            $table->string('shipping_postal_code');
            $table->string('shipping_street_address');
            $table->string('shipping_municipality')->nullable();
            $table->text('shipping_ordernote')->nullable();

            // Order Summary
            $table->string('current_status')->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('discounted_total', 10, 2);
            $table->decimal('net_total', 10, 2);
            $table->timestamp('last_status_updated')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
