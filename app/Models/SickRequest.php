<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SickRequest extends Model
{
    use HasFactory;

   protected $fillable = [
    'user_id',
    'start_date',
    'end_date',
    'reason',
    'type', // ✅ MUST BE HERE
    'status',
    'sick_note',
    'admin_id', // 🔥 IMPORTANT
];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}