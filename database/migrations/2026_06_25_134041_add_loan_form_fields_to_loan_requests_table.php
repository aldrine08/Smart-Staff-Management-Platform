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
    Schema::table('loan_requests', function (Blueprint $table) {

        $table->date('recovery_start_date')->nullable();

        $table->text('special_notes')->nullable();

        $table->string('guarantor1_name')->nullable();
        $table->string('guarantor1_phone')->nullable();
        $table->string('guarantor1_id')->nullable();

        $table->string('guarantor2_name')->nullable();
        $table->string('guarantor2_phone')->nullable();
        $table->string('guarantor2_id')->nullable();

        $table->string('guarantor3_name')->nullable();
        $table->string('guarantor3_phone')->nullable();
        $table->string('guarantor3_id')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_requests', function (Blueprint $table) {
            //
        });
    }
};
