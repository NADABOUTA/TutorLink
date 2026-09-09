<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ajouter la colonne role à la table users
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 20)->default('apprenant')->after('email')->index();
            });
        }

        // 2. Transférer les rôles existants depuis la table Laratrust role_user si elle existe
        if (Schema::hasTable('role_user') && Schema::hasTable('roles')) {
            $userRoles = DB::table('role_user')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->select('role_user.user_id', 'roles.name as role_name')
                ->get();

            foreach ($userRoles as $ur) {
                DB::table('users')
                    ->where('id', $ur->user_id)
                    ->update(['role' => $ur->role_name]);
            }
        }

        // 3. Supprimer les tables de Laratrust
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
