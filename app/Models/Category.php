<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'meta_title',
        'meta_description',
        'image',
        'imagefiletag',
        'alttext',
        'icon_image',
        'show_in_homepage',
        'short_description',
        'position',
    ];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
