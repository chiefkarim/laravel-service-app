<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {

            $table->dropForeign(['user_id']);

            $table->dropColumn('user_id');

            $table->unique(['resource', 'operation']);
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->unique(['user_id', 'service', 'operation']);
        });
    }
};
