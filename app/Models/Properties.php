<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Properties extends Model
{
    protected $table = 'properties';

    protected $fillable = [
        'user_id', 'property_name', 'property_desc', 'property_location', 'property_price',
    ];

    public function files()
    {
        return $this->hasMany(PropertyFiles::class, 'property_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function visitsCount()
    {
        return $this->hasMany(Visits::class, 'property_id')->count();
    }

    public function stars()
    {
        $user_id = auth()->id(); 

        return $this->hasMany(Stars::class, 'property_id', 'id')->where('user_id', $user_id);
    }

    public function averageStars()
    {
        return $this->hasMany(Stars::class, 'property_id', 'id')->avg('star');
    }
}
