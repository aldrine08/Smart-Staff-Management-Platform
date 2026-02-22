<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'days_present',
        'base_salary',
        'total_bonus',
        'total_deductions',
        'net_salary'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
