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
    Schema::table('audit_logs', function (Blueprint $table) {
        $table->foreignId('admin_id')->after('id')->constrained('users');
        $table->foreignId('staff_id')->after('admin_id')->constrained('users');
        $table->string('action')->after('staff_id');
    });
}

public function down(): void
{
    Schema::table('audit_logs', function (Blueprint $table) {
        $table->dropColumn(['admin_id', 'staff_id', 'action']);
    });
}
};
