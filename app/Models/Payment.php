<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id', 'order_id', 'payment_reference', 'amount', 'payment_method'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
