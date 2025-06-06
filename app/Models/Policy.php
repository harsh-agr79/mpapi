<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends BaseModel
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'meta_title',
        'meta_description',];
}
