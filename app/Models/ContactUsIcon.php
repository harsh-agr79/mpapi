<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUsIcon extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'url',
    ];
}
