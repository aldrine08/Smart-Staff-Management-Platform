<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public function isAdmin()
{
    return $this->role === 'admin';
}

public function attendances()
{
    return $this->hasMany(Attendance::class);
}

    protected $fillable = [
        'name',
        'unit_id',
        'department_id',
        'email',
        'password',
        'phone',
        'avatar',
        'start_date',
        'document',
        'employment_number',
    'marital_status',
    'spouse_name',
    'children',
    'next_of_kin',
    'next_of_kin_contact',
    'academic_qualifications',
    'physical_disability',
    'id_number',
    'dob',
    'district',
    'division',
    'ethnicity',
    'physical_address',
    'gender',
    'start_date',
    'role',
    'is_active',
    'admin_id',

    ];

    protected $casts = [
    'children' => 'array',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function unit()
{
    return $this->belongsTo(Unit::class);
}

public function department()
{
    return $this->belongsTo(Department::class);
}

public function payrolls()
{
    return $this->hasMany(\App\Models\Payroll::class);
}

public function items()
{
    return $this->belongsToMany(Item::class)
        ->withPivot('assigned_by','assigned_at','returned_at','status','condition_notes')
        ->withTimestamps();
}

public function messages() {
    return $this->hasMany(Message::class);
}

public function mentions() {
    return $this->belongsToMany(Message::class, 'message_mentions', 'mentioned_user_id', 'message_id')->withTimestamps();
}

public function documents()
{
    return $this->hasMany(\App\Models\StaffDocument::class);
}

public function units()
{
    return $this->hasMany(Unit::class, 'admin_id');
}

public function scopeAdmins($query)
{
    return $query->where('role', 'admin');
}

public function scopeActive($query)
{
    return $query->where('is_active', 1);
}

public function deactivateWithRelations()
{
    $this->is_active = 0;
    $this->save();

    // deactivate all units under this admin
    foreach ($this->units as $unit) {
        foreach ($unit->staff as $staff) {
            $staff->is_active = 0;
            $staff->save();
        }
    }
}

public function activateWithRelations()
{
    $this->is_active = 1;
    $this->save();

    foreach ($this->units as $unit) {
        foreach ($unit->staff as $staff) {
            $staff->is_active = 1;
            $staff->save();
        }
    }
}


public function isSuperAdmin()
{
    return $this->role === 'super_admin';
}

public function loanRequests()
{
    return $this->hasMany(\App\Models\LoanRequest::class);
}

public function loans()
{
    return $this->hasMany(\App\Models\Loan::class);
}

public function activeLoan()
{
    return $this->hasOne(Loan::class)->where('status', 'active');
}

public function hasActiveLoan()
{
    return $this->loans()->where('status', 'active')->exists();
}

}


