<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            // Renommer 'service' en 'resource'
            $table->renameColumn('service', 'resource');

            // Si tu veux aussi t’assurer que les colonnes et contraintes sont en place :
            // $table->string('operation')->change(); // si déjà existante
            // $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade')->change();
            // $table->unique(['user_id', 'resource', 'operation']); // attention aux doublons existants !
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->renameColumn('resource', 'service');
        });
    }
};
