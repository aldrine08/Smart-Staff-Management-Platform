<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClockInSetting extends Model
{
     use HasFactory;

    protected $fillable = [
        'start_time',
        'working_days',
        'admin_id',
    ];

    protected $casts = [
        'working_days' => 'array', // automatically cast JSON to array
    ];
}
