<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visits extends Model
{
    use HasFactory;

    protected $table = 'visits';

    protected $fillable = [
        'user_id',
        'property_id',
    ];
}
