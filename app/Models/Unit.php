<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ChatRoom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit  extends Model
{
    protected $fillable = ['name', 'latitude', 'longitude', 'radius'];
     use HasFactory;

    public function staff()
    {
        return $this->hasMany(User::class, 'unit_id');
    }

    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class, 'unit_id'); // ✅ Corrected
    }


}
