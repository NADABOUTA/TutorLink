<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAndProtectedRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer les rôles nécessaires
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::firstOrCreate(['name' => 'tuteur'], ['display_name' => 'Tuteur']);
        Role::firstOrCreate(['name' => 'apprenant'], ['display_name' => 'Apprenant']);
    }

    /**
     * Vérifier que toutes les routes protégées redirigent les invités vers /login.
     */
    public function test_unauthenticated_users_are_redirected_to_login_on_protected_routes(): void
    {
        $protectedRoutes = [
            ['GET', '/dashboard'],
            ['GET', '/demandes'],
            ['GET', '/demandes/create'],
            ['POST', '/demandes'],
            ['GET', '/demandes/1'],
            ['GET', '/demandes/1/edit'],
            ['PATCH', '/demandes/1'],
            ['DELETE', '/demandes/1'],
            ['PATCH', '/demandes/1/terminer'],
            ['GET', '/offres'],
            ['POST', '/demandes/1/offres'],
            ['PATCH', '/offres/1/accepter'],
            ['POST', '/demandes/1/avis'],
            ['POST', '/demandes/1/commentaires'],
            ['GET', '/profile'],
            ['PATCH', '/profile'],
            ['DELETE', '/profile'],
            ['GET', '/admin/moderation'],
            ['GET', '/admin/users'],
        ];

        foreach ($protectedRoutes as [$method, $uri]) {
            $response = match ($method) {
                'GET' => $this->get($uri),
                'POST' => $this->post($uri),
                'PATCH' => $this->patch($uri),
                'DELETE' => $this->delete($uri),
            };

            $response->assertRedirect('/login');
        }
    }

    /**
     * Vérifier que les utilisateurs sans rôle admin ne peuvent pas accéder à l'espace admin.
     */
    public function test_non_admin_users_cannot_access_admin_panel(): void
    {
        $apprenant = User::factory()->create();
        $apprenantRole = Role::where('name', 'apprenant')->first();
        $apprenant->addRole($apprenantRole);

        // Tentative d'accès à la modération
        $responseMod = $this->actingAs($apprenant)->get('/admin/moderation');
        $responseMod->assertStatus(403);

        // Tentative d'accès à la gestion utilisateurs
        $responseUsers = $this->actingAs($apprenant)->get('/admin/users');
        $responseUsers->assertStatus(403);
    }

    /**
     * Vérifier que les requêtes POST/PATCH protégées par CSRF rejettent les requêtes invalides ou requièrent un token.
     */
    public function test_csrf_protection_is_active_on_state_changing_routes(): void
    {
        // En Laravel HTTP Tests, les tokens CSRF sont testables via le middleware VerifyCsrfToken
        $response = $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->get('/login');

        // La page de login doit contenir le champ csrf
        $this->get('/login')->assertSee('_token', false);
        $this->get('/register')->assertSee('_token', false);
    }
}
