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
        Schema::create('item_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
             $table->foreignId('item_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
    $table->timestamp('assigned_at')->nullable();
    $table->timestamp('returned_at')->nullable();
    $table->string('status')->default('assigned'); // assigned, returned
    $table->text('condition_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_user');
    }
};
