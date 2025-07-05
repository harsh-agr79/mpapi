<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'company',
        'message',
    ];
}
