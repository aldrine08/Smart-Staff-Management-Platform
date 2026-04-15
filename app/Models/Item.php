<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name','category','serial_number',
        'unit_id','department_id',
        'quantity','notes',
        'assigned_to','assigned_by','assigned_at',
        'returned_at','status','condition_notes',
        'admin_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('assigned_by','assigned_at','returned_at','status','condition_notes')
            ->withTimestamps();
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

