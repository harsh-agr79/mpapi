<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'customer_id', 'product_id', 'quantity', 'color', 'price', 'discounted_price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::saved(fn ($item) => $item->updateOrderTotals());
        static::deleted(fn ($item) => $item->updateOrderTotals());
    }

    public function updateOrderTotals()
    {
        $order = $this->order; // Get parent order
        if (!$order) return;
    
        $totals = $order->OrderItem()
            ->selectRaw('
                SUM(price * quantity) as total_amount,
                SUM((price - discounted_price) * quantity) as discount,
                SUM(discounted_price * quantity) as discounted_total
            ')
            ->first();
    
        $order->update([
            'total_amount' => $totals->total_amount ?? 0,
            'discount' => $totals->discount ?? 0,
            'discounted_total' => $totals->discounted_total ?? 0,
            'net_total' => $totals->discounted_total ?? 0, // Assuming net_total = discounted_total
        ]);
    }
    
}
