<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageSlider extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'image',
        'main_text',
        'sub_text',
        'button_text',
        'button_link',
    ];
}
