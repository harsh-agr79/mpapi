<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_amount',
        'minimum_order_amount',
        'start_date',
        'end_date',
        'is_active',
        'used_count', // <- added
        'description',
        'applies_to_products',
        'applies_to_categories',
    ];
    
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'used_count' => 'integer', // <- added
        'applies_to_products' => 'array',
        'applies_to_categories' => 'array',
    ];

    public function isValid()
    {
        // Get the current date
        $currentDate = now();

        // Check if the current date is within the start and end date
        if ($currentDate->greaterThanOrEqualTo($this->start_date) && $currentDate->lessThanOrEqualTo($this->end_date)) {
            return 'Valid';
        }

        return 'Invalid';
    }
}
