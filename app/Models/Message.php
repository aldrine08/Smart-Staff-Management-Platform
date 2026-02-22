<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['chat_room_id','user_id','updated_at','content'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function chatRoom() {
        return $this->belongsTo(ChatRoom::class);
    }

    public function mentions() {
        return $this->belongsToMany(User::class, 'message_mentions', 'message_id','mentioned_user_id')->withTimestamps();
    }
}
