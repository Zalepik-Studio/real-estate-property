<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stars extends Model
{
    use HasFactory;

    protected $table = 'stars';

    protected $fillable = [
        'user_id',
        'property_id',
        'star',
    ];
}
