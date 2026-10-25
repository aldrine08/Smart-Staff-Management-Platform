
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_off_day_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                ->unique()
                ->constrained('departments')
                ->restrictOnDelete();

            $table->boolean('is_enabled')->default(false);

            // Monthly off-day entitlement, e.g. 1.75
            $table->decimal('monthly_accrual', 8, 2)
                ->default(1.75);

            // Maximum unused days that may carry into a new year
            $table->decimal('carryover_cap', 8, 2)
                ->default(10.00);

            // Accrual starts from each employee's joining date
            $table->string('accrual_start_rule')
                ->default('joining_date');

            // Approved requests will count calendar days
            $table->string('day_counting_rule')
                ->default('calendar_days');

            // Existing approvals are excluded from automatic backfill
            $table->string('historical_request_rule')
                ->default('forward_only');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_off_day_settings');
    }
};
