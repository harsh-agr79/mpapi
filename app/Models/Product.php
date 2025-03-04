<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unique_id',
        'category_id',
        'subcategory_ids',
        'price',
        'outofstock',
        'hidden',
        'featured',
        'newarrival',
        'details',
        'specifications',
        'image_1',
        'image_1_alt',
        'image_2',
        'image_2_alt',
        'image_3',
        'image_3_alt',
        'image_4',
        'image_4_alt',
        'image_5',
        'image_5_alt',
        'colors',
        'sku',
        'short_description',
        'meta_title',
        'meta_description',
        'discounted_price', 
        'ordernum',
    ];

    protected $casts = [
        'subcategory_ids' => 'array',
        'specifications' => 'array',
        'colors' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'product_subcategory');
    }

    public function subcategory() {
       // Check if subcategory_ids is not null and is an array
        if (!empty($this->subcategory_ids) && is_array($this->subcategory_ids)) {
            return Subcategory::whereIn('id', $this->subcategory_ids)->get();
        }
        return collect();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

