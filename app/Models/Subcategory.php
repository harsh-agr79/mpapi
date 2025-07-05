<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends BaseModel
{
    use HasFactory, SoftDeletes;

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
