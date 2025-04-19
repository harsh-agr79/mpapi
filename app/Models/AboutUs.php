<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'meta_title',
        'meta_description',
        'our_vision',
    'vision_pic',
    'mds_voice',
    'cover_pic',
    ];
}
