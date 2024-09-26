<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    protected $table = 'chats';

    protected $fillable = [
        'message_id',
        'chat_id',
        'sender_id',
        'receiver_id',
        'message',
        'chat_label',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function files()
    {
        return $this->hasMany(ChatFiles::class, 'chat_id', 'id');
    }
}
