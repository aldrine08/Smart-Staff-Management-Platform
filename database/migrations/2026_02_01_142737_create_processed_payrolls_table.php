<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('processed_payrolls', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
    $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
    $table->date('start_date');
    $table->date('end_date');
    $table->integer('days_present')->default(0);
    $table->decimal('base_salary', 10, 2)->default(0);
    $table->decimal('total_bonus', 10, 2)->default(0);
    $table->decimal('total_deductions', 10, 2)->default(0);
    $table->decimal('net_salary', 10, 2)->default(0);
    $table->timestamp('processed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processed_payrolls');
    }
};
