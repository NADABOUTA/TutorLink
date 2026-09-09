<?php

namespace Database\Seeders;

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
        // Utilisateur administrateur par défaut
        User::updateOrCreate(
            ['email' => 'admin@tutorlink.com'],
            [
                'name' => 'Admin TutorLink',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'telephone' => '0600000000',
            ]
        );

        // Utilisateur tuteur par défaut
        User::updateOrCreate(
            ['email' => 'tuteur@test.com'],
            [
                'name' => 'NADA',
                'role' => 'tuteur',
                'password' => Hash::make('password'),
                'telephone' => '0611223344',
                'bio' => 'Professeur passionné et expérimenté en Mathématiques et Physique.',
                'matiere' => 'Mathématiques',
                'tarif_horaire' => 150,
            ]
        );

        // Utilisateur apprenant par défaut
        User::updateOrCreate(
            ['email' => 'apprenant@test.com'],
            [
                'name' => 'TEST',
                'role' => 'apprenant',
                'password' => Hash::make('password'),
                'telephone' => '0655443322',
            ]
        );
    }
}
