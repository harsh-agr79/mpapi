<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignImage extends BaseModel
{
    use HasFactory;
    protected $fillable = ['path', 'white_text', 'yellow_text'];
}
