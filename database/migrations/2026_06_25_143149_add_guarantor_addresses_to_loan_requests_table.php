<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void { Schema::table('loan_requests', function (Blueprint $table) { $table->string('guarantor1_address')->nullable(); $table->string('guarantor2_address')->nullable(); $table->string('guarantor3_address')->nullable(); }); } public function down(): void { Schema::table('loan_requests', function (Blueprint $table) { $table->dropColumn([ 'guarantor1_address', 'guarantor2_address', 'guarantor3_address' ]); }); }
};
