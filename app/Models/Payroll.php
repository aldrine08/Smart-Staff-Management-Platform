<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Bonus;
use App\Models\Deduction;


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

    public function bonuses()
{
    return $this->hasMany(Bonus::class); // or belongsToMany if using pivot
}

public function deductions()
{
    return $this->hasMany(Deduction::class); // or belongsToMany if using pivot
}


}


