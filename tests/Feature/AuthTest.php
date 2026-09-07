<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::firstOrCreate(['name' => 'tuteur'], ['display_name' => 'Tuteur']);
        Role::firstOrCreate(['name' => 'apprenant'], ['display_name' => 'Apprenant']);
    }

    /**
     * Test inscription d'un apprenant avec attribution du rôle Laratrust.
     */
    public function test_apprenant_can_register_and_receives_apprenant_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Karim Alami',
            'email' => 'karim@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'apprenant',
            'telephone' => '0612345678',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'karim@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('apprenant'));
        $this->assertFalse($user->hasRole('tuteur'));
    }

    /**
     * Test inscription d'un tuteur avec attribution du rôle Laratrust.
     */
    public function test_tuteur_can_register_and_receives_tuteur_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Professeur Ahmed',
            'email' => 'ahmed@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'tuteur',
            'telephone' => '0698765432',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'ahmed@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('tuteur'));
        $this->assertFalse($user->hasRole('apprenant'));
    }

    /**
     * Test validation lors de l'inscription (rôle invalide, email dupliqué, etc.).
     */
    public function test_registration_validation_rules(): void
    {
        // Rôle invalide (ex: tentative de s'auto-attribuer admin)
        $response = $this->post('/register', [
            'name' => 'Hacker User',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);
        $response->assertSessionHasErrors('role');

        // Email déjà existant
        User::factory()->create(['email' => 'existant@example.com']);
        $responseDuplicate = $this->post('/register', [
            'name' => 'Second User',
            'email' => 'existant@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'apprenant',
        ]);
        $responseDuplicate->assertSessionHasErrors('email');

        // Mots de passe non concordants
        $responseMismatch = $this->post('/register', [
            'name' => 'Third User',
            'email' => 'third@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
            'role' => 'apprenant',
        ]);
        $responseMismatch->assertSessionHasErrors('password');
    }

    /**
     * Test connexion réussie.
     */
    public function test_user_can_authenticate_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login_test@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login_test@example.com',
            'password' => 'secret123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    /**
     * Test échec de connexion avec mot de passe incorrect.
     */
    public function test_user_cannot_authenticate_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'wrong_pass@example.com',
            'password' => bcrypt('correct123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'wrong_pass@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test déconnexion utilisateur.
     */
    public function test_user_can_logout_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
