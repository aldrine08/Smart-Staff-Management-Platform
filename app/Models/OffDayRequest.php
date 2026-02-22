<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class OffDayRequest extends Model
{
    use HasFactory;

   protected $fillable = [
        'user_id',
        'reason',
        'start_date',
        'end_date',
        'status',
    ];

    // 🔗 Relationship: OffDay belongs to User
   public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}
}
