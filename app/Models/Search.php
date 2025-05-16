<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Search extends Model {
    use HasFactory;

    protected $fillable = [
        'term',
    ];

    protected function searchCount(): Attribute
    {
        return Attribute::make(
            get: fn () => static::where('term', $this->term)->count(),
        );
    }

    
}
