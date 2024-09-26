<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyFiles extends Model
{
    protected $table = 'property_files';

    protected $fillable = [
        'property_id', 'property_file',
    ];

    public function addProperty()
    {
        return $this->belongsTo(Properties::class);
    }
}
