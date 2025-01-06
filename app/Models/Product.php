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
        return Subcategory::whereIn('id', $this->subcategory_ids)->get();
    }
}

