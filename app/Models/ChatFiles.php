<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatFiles extends Model
{
    protected $table = 'chat_files';

    protected $fillable = [
        'chat_id', 'file',
    ];
}
