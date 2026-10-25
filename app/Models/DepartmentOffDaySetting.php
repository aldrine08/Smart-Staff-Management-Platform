<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentOffDaySetting extends Model
{
    protected $fillable = [
        'department_id',
        'is_enabled',
        'monthly_accrual',
        'carryover_cap',
        'accrual_start_rule',
        'day_counting_rule',
        'historical_request_rule',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'monthly_accrual' => 'decimal:2',
        'carryover_cap' => 'decimal:2',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
