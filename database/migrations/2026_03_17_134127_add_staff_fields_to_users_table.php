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
    Schema::table('users', function (Blueprint $table) {

        $table->string('employment_number')->nullable();

        $table->string('marital_status')->nullable();
        $table->string('spouse_name')->nullable();

        $table->json('children')->nullable(); // store children as JSON

        $table->string('next_of_kin')->nullable();
        $table->string('next_of_kin_contact')->nullable();

        $table->text('academic_qualifications')->nullable();
        $table->string('physical_disability')->nullable();

        $table->string('id_number')->nullable();
        $table->date('dob')->nullable();

        $table->string('district')->nullable();
        $table->string('division')->nullable();
        $table->string('ethnicity')->nullable();

        $table->string('physical_address')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
