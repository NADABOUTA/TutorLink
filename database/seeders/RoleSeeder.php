<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrateur',
                'description' => 'Gestion de la plateforme, modération et suivi des utilisateurs',
            ]
        );

        $tuteurRole = Role::firstOrCreate(
            ['name' => 'tuteur'],
            [
                'display_name' => 'Tuteur',
                'description' => 'Propose des cours particuliers et répond aux demandes des apprenants',
            ]
        );

        $apprenantRole = Role::firstOrCreate(
            ['name' => 'apprenant'],
            [
                'display_name' => 'Apprenant',
                'description' => 'Recherche un accompagnement pédagogique et publie des demandes',
            ]
        );

        // Utilisateur administrateur par défaut
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@tutorlink.com'],
            [
                'name' => 'Admin TutorLink',
                'password' => Hash::make('password'),
                'telephone' => '0600000000',
            ]
        );

        if (!$adminUser->hasRole('admin')) {
            $adminUser->addRole($adminRole);
        }
    }
}
