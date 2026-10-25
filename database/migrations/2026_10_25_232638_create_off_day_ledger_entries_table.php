<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('off_day_ledger_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('department_id')
                ->constrained('departments')
                ->restrictOnDelete();

            // Examples: accrual:2026-01, carryover:2027
            $table->string('entry_key', 100);

            // Stage 2: accrual, carryover_expiration
            // Stage 3: approved_usage
            $table->string('entry_type', 40);

            // 175 represents 1.75 days.
            // Negative values represent deductions.
            $table->bigInteger('amount_hundredths');

            $table->date('effective_on');

            $table->text('description')->nullable();

            $table->timestamps();

            // A given employee can receive each event only once.
            $table->unique(['user_id', 'entry_key']);

            $table->index(['user_id', 'effective_on']);
            $table->index(['department_id', 'effective_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('off_day_ledger_entries');
    }
};
