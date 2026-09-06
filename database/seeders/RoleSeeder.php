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
        $adminUser = User::updateOrCreate(
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

        // Utilisateur tuteur par défaut
        $tuteurUser = User::updateOrCreate(
            ['email' => 'tuteur@test.com'],
            [
                'name' => 'NADA',
                'password' => Hash::make('password'),
                'telephone' => '0611223344',
                'bio' => 'Professeur passionné et expérimenté en Mathématiques et Physique.',
                'matiere' => 'Mathématiques',
                'tarif_horaire' => 150,
            ]
        );
        if (!$tuteurUser->hasRole('tuteur')) {
            $tuteurUser->addRole($tuteurRole);
        }

        // Utilisateur apprenant par défaut
        $apprenantUser = User::updateOrCreate(
            ['email' => 'apprenant@test.com'],
            [
                'name' => 'TEST',
                'password' => Hash::make('password'),
                'telephone' => '0655443322',
            ]
        );
        if (!$apprenantUser->hasRole('apprenant')) {
            $apprenantUser->addRole($apprenantRole);
        }
    }
}
