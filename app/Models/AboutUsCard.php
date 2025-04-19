<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'text',
        'our_vision',
        'vision_pic',
        'mds_voice',
        'cover_pic',
    ];
}
