<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    protected $fillable = [
        'loan_id',
        'amount_paid',
        'payment_date',
        'remaining_balance',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}