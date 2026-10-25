<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OffDayLedgerEntry extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'entry_key',
        'entry_type',
        'amount_hundredths',
        'effective_on',
        'description',
    ];

    protected $casts = [
        'amount_hundredths' => 'integer',
        'effective_on' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
