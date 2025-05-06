<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageSupport extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'main_text',
        'sub_text',
        'button_link',
    ];
}
