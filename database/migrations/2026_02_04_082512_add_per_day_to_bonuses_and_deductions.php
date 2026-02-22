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
    Schema::table('bonuses', function (Blueprint $table) {
        $table->boolean('per_day')->default(false);
    });

    Schema::table('deductions', function (Blueprint $table) {
        $table->boolean('per_day')->default(false);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bonuses_and_deductions', function (Blueprint $table) {
            //
        });
    }
};
