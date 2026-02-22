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
        Schema::create('salary_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade'); // assumes you have units table
            $table->foreignId('department_id')->constrained()->onDelete('cascade'); // assumes you have departments table
            $table->decimal('daily_salary', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_settings');
    }
};
