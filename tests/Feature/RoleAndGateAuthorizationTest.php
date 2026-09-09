<?php

namespace Tests\Feature;

use App\Models\Demande;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAndGateAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $apprenant;
    protected User $autreApprenant;
    protected User $tuteur;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apprenant = User::factory()->apprenant()->create(['name' => 'Apprenant 1']);
        $this->autreApprenant = User::factory()->apprenant()->create(['name' => 'Apprenant 2']);
        $this->tuteur = User::factory()->tuteur()->create(['name' => 'Tuteur']);
        $this->admin = User::factory()->admin()->create(['name' => 'Admin']);
    }

    /**
     * Un tuteur ne peut pas créer de demande (réservé aux apprenants via Policy).
     */
    public function test_tuteur_cannot_create_demande(): void
    {
        // 1. Accès au formulaire de création
        $getCreate = $this->actingAs($this->tuteur)->get('/demandes/create');
        $getCreate->assertStatus(403);

        // 2. Soumission POST de création
        $postCreate = $this->actingAs($this->tuteur)->post('/demandes', [
            'matiere' => 'Physique',
            'niveau' => 'Lycée',
            'description' => 'Tentative non autorisée par un tuteur.',
            'budget' => 100,
        ]);
        $postCreate->assertStatus(403);
    }

    /**
     * Un utilisateur ne peut pas modifier ou supprimer la demande d'un autre utilisateur.
     */
    public function test_user_cannot_edit_or_delete_another_users_demande(): void
    {
        $demande = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Informatique',
            'niveau' => 'Lycée',
            'description' => 'Demande originale.',
            'budget' => 120,
            'statut' => 'ouverte',
        ]);

        // Tentative d'accès à l'édition par un autre apprenant
        $editResp = $this->actingAs($this->autreApprenant)->get(route('demandes.edit', $demande));
        $editResp->assertStatus(403);

        // Tentative de mise à jour PATCH par un autre apprenant
        $patchResp = $this->actingAs($this->autreApprenant)->patch(route('demandes.update', $demande), [
            'matiere' => 'Matière modifiée pirate',
            'niveau' => 'Lycée',
            'description' => 'Description pirate avec au moins 30 caractères pour passer la validation.',
            'budget' => 200,
        ]);
        $patchResp->assertStatus(403);

        // Tentative de suppression DELETE par un autre apprenant
        $deleteResp = $this->actingAs($this->autreApprenant)->delete(route('demandes.destroy', $demande));
        $deleteResp->assertStatus(403);

        // Vérifier que la demande est intacte en base
        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'matiere' => 'Informatique',
        ]);
    }

    /**
     * Un tuteur ne peut pas consulter une demande en cours de modération.
     */
    public function test_tuteur_cannot_view_demande_in_moderation(): void
    {
        $demandeEnModeration = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Sciences',
            'niveau' => 'Collège',
            'description' => 'Attente approbation.',
            'budget' => 90,
            'statut' => 'en_attente_moderation',
        ]);

        // Le tuteur tente de voir la demande en attente
        $response = $this->actingAs($this->tuteur)->get(route('demandes.show', $demandeEnModeration));
        $response->assertStatus(403);

        // L'apprenant auteur a le droit de la voir
        $authorResponse = $this->actingAs($this->apprenant)->get(route('demandes.show', $demandeEnModeration));
        $authorResponse->assertStatus(200);

        // L'admin a le droit de la voir
        $adminResponse = $this->actingAs($this->admin)->get(route('demandes.show', $demandeEnModeration));
        $adminResponse->assertStatus(200);
    }

    /**
     * Seul l'administrateur peut accéder à l'espace d'administration via la Gate admin.
     */
    public function test_only_admin_can_access_admin_panel(): void
    {
        // Un apprenant tente d'accéder
        $respApprenant = $this->actingAs($this->apprenant)->get('/admin/moderation');
        $respApprenant->assertStatus(403);

        // Un tuteur tente d'accéder
        $respTuteur = $this->actingAs($this->tuteur)->get('/admin/users');
        $respTuteur->assertStatus(403);

        // L'administrateur accède avec succès
        $respAdminMod = $this->actingAs($this->admin)->get('/admin/moderation');
        $respAdminMod->assertStatus(200);

        $respAdminUsers = $this->actingAs($this->admin)->get('/admin/users');
        $respAdminUsers->assertStatus(200);
    }
}
