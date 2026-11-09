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
        'reporting_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reporting_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}