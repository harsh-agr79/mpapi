<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'order_date', 'billing_full_name', 'billing_phone_number',
        'payment_status',
        'billing_country_region', 'billing_city', 'billing_state', 'billing_email',
        'billing_postal_code', 'shipping_full_name', 'shipping_phone_number',
        'shipping_country_region', 'shipping_city', 'shipping_state',
        'shipping_email', 'shipping_postal_code', 'billing_street_address',
        'billing_municipality', 'billing_ordernote', 'shipping_street_address',
        'shipping_municipality', 'shipping_ordernote', 'current_status',
        'total_amount', 'delivery_charge', 'discount', 'discounted_total', 
        'net_total', 'last_status_updated',
        'coupon_code',
          'coupon_discount',
            'free_shipping'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($order) {
            if ($order->isDirty('current_status')) { // Check if status has changed
                OrderStatusHistory::create([
                    'order_id'   => $order->id,
                    'status'     => $order->current_status,
                    'changed_at' => now(),
                    'user_id'    => auth()->id() ?? null, // If authenticated, store user ID
                ]);
                
                if ($order->customer && $order->customer->email) {
                    Mail::to($order->customer->email)->send(new OrderStatusUpdated($order));
                }
            }
           
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function OrderItem()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
