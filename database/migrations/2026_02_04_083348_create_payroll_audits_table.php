<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('payroll_audits', function (Blueprint $table) {
        $table->id();
        $table->foreignId('admin_id')->constrained('users');
        $table->foreignId('unit_id');
        $table->date('start_date');
        $table->date('end_date');
        $table->decimal('total_paid', 12, 2);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_audits');
    }
};
