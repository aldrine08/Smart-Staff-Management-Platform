<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    protected $fillable = [
    'user_id',
    'amount_requested',
    'reason',
    'repayment_months',

    'recovery_start_date',
    'special_notes',

    'guarantor1_name',
    'guarantor1_phone',
    'guarantor1_id',
    'guarantor1_address',

    'guarantor2_name',
    'guarantor2_phone',
    'guarantor2_id',
    'guarantor2_address',
    
    'guarantor3_name',
    'guarantor3_phone',
    'guarantor3_id',
    'guarantor3_address',

    'status',
    'admin_reason',
    'reviewed_by',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}