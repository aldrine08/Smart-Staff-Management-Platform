<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AuditLog extends Model
{
    protected $fillable = [
    'admin_id',
    'staff_id',
    'action',
];

public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}

public function staff()
{
    return $this->belongsTo(User::class, 'staff_id');
}
}
