<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase9AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_users_index(): void
    {
        $apprenant = User::factory()->create();
        $apprenant->addRole('apprenant');

        $response = $this->actingAs($apprenant)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_users_list(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');

        $user1 = User::factory()->create(['name' => 'Karim Alami', 'email' => 'karim@example.com']);
        $user1->addRole('tuteur');

        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertOk();
        $response->assertSee('Karim Alami');
        $response->assertSee('karim@example.com');
        $response->assertSee('Gestion des Utilisateurs');
    }

    public function test_admin_can_toggle_user_status(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');

        $targetUser = User::factory()->create(['is_active' => true]);
        $targetUser->addRole('tuteur');

        // 1. Désactiver
        $response = $this->actingAs($admin)->patch(route('admin.users.toggle', $targetUser));
        $response->assertSessionHas('success');
        $this->assertFalse($targetUser->refresh()->is_active);

        // 2. Réactiver
        $response = $this->actingAs($admin)->patch(route('admin.users.toggle', $targetUser));
        $response->assertSessionHas('success');
        $this->assertTrue($targetUser->refresh()->is_active);
    }

    public function test_admin_cannot_deactivate_themselves(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->addRole('admin');

        $response = $this->actingAs($admin)->patch(route('admin.users.toggle', $admin));
        $response->assertSessionHas('error');
        $this->assertTrue($admin->refresh()->is_active);
    }

    public function test_inactive_user_is_blocked_by_middleware(): void
    {
        $inactiveUser = User::factory()->create(['is_active' => false]);
        $inactiveUser->addRole('apprenant');

        // Lorsqu'un utilisateur inactif tente d'accéder au dashboard, il est déconnecté et redirigé vers /login
        $response = $this->actingAs($inactiveUser)->get(route('dashboard'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
