<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id', // Foreign key for the relationship
    ];

    // Define the relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}