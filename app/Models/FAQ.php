<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends BaseModel
{
    use HasFactory;

    protected $fillable = ['question', 'answer', 'order'];
}
