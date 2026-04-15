<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Message;
use App\Models\User;



class ChatRoom extends Model
{
    protected $fillable = ['name','unit','admin_id'];
    use HasFactory;


    public function users()
{
    return $this->belongsToMany(User::class, 'chat_room_user');
}


    public function messages() {
        return $this->hasMany(Message::class);
    }
}
