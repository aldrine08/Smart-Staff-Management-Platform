<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
        'avatar'
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


}


