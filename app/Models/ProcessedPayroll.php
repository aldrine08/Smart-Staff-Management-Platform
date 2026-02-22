<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessedPayroll extends Model
{
    protected $fillable = [
        'user_id', 'unit_id', 'department_id', 'start_date', 'end_date', 'net_salary', 'processed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
