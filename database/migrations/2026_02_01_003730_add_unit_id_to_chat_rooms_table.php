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
    Schema::table('chat_rooms', function (Blueprint $table) {
        $table->foreignId('unit_id')->nullable()->constrained()->cascadeOnDelete();
    });
}

public function down()
{
    Schema::table('chat_rooms', function (Blueprint $table) {
        $table->dropColumn('unit_id');
    });
}
};
