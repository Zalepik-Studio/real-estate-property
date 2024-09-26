<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class User extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;

    protected $fillable = [
        'name',
        'profile_picture',
        'email',
        'phone_number',
        'role',
        'password',
        'token',
        'token_expired_at', 
    ];

    protected $hidden = [
        'password',
    ];

    public function properties()
    {
        return $this->hasMany(Properties::class, 'user_id', 'id');
    }
}
