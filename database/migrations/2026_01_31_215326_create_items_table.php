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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
    $table->string('category')->nullable();
    $table->string('serial_number')->nullable();
    $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
    $table->integer('quantity')->default(1);
    $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
