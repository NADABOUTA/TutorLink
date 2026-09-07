<?php

namespace Tests\Feature;

use App\Models\Demande;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemandeCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $apprenant;
    protected User $tuteur;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::firstOrCreate(['name' => 'tuteur'], ['display_name' => 'Tuteur']);
        Role::firstOrCreate(['name' => 'apprenant'], ['display_name' => 'Apprenant']);

        $this->apprenant = User::factory()->create();
        $this->apprenant->addRole('apprenant');

        $this->tuteur = User::factory()->create(['matiere' => 'Physique-Chimie', 'tarif_horaire' => 120]);
        $this->tuteur->addRole('tuteur');

        $this->admin = User::factory()->create();
        $this->admin->addRole('admin');
    }

    /**
     * Test création d'une demande par un apprenant avec statut initial en_attente_moderation.
     */
    public function test_apprenant_can_create_demande_with_initial_statut_en_attente_moderation(): void
    {
        $response = $this->actingAs($this->apprenant)->post('/demandes', [
            'matiere' => 'Mathématiques Supérieures',
            'niveau' => 'Supérieur / Université',
            'description' => 'Besoin d\'aide sur les équations différentielles et l\'algèbre linéaire.',
            'budget' => 250,
        ]);

        $response->assertRedirect(route('demandes.index'));
        $this->assertDatabaseHas('demandes', [
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Mathématiques Supérieures',
            'niveau' => 'Supérieur / Université',
            'budget' => 250,
            'statut' => 'en_attente_moderation',
        ]);
    }

    /**
     * Test affichage et filtrage par rôle :
     * L'apprenant voit ses propres demandes (y compris en attente),
     * Le tuteur ne voit que les demandes ouvertes approuvées.
     */
    public function test_demandes_index_filtering_by_role(): void
    {
        // Demande en modération
        $demandeEnAttente = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'SVT Bio',
            'niveau' => 'Lycée',
            'description' => 'Demande en cours d\'examen par la modération.',
            'budget' => 150,
            'statut' => 'en_attente_moderation',
        ]);

        // Demande ouverte approuvée
        $demandeOuverte = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Physique Mécanique',
            'niveau' => 'Baccalauréat',
            'description' => 'Demande ouverte aux candidatures.',
            'budget' => 200,
            'statut' => 'ouverte',
        ]);

        // 1. L'apprenant consulte la liste
        $respApprenant = $this->actingAs($this->apprenant)->get('/demandes');
        $respApprenant->assertStatus(200);
        $respApprenant->assertSee('SVT Bio');
        $respApprenant->assertSee('Physique Mécanique');

        // 2. Le tuteur consulte la liste
        $respTuteur = $this->actingAs($this->tuteur)->get('/demandes');
        $respTuteur->assertStatus(200);
        $respTuteur->assertSee('Physique Mécanique');
        $respTuteur->assertDontSee('SVT Bio'); // Non visible aux tuteurs car en modération
    }

    /**
     * Test consultation des détails d'une demande.
     */
    public function test_user_can_view_demande_details(): void
    {
        $demande = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Informatique Python',
            'niveau' => 'Supérieur / Université',
            'description' => 'Accompagnement en algorithmique et programmation objet.',
            'budget' => 180,
            'statut' => 'ouverte',
        ]);

        $response = $this->actingAs($this->apprenant)->get(route('demandes.show', $demande));
        $response->assertStatus(200);
        $response->assertSee('Informatique Python');
        $response->assertSee('180');
    }

    /**
     * Test mise à jour d'une demande par son créateur.
     */
    public function test_apprenant_can_update_own_demande(): void
    {
        $demande = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Anglais Oral',
            'niveau' => 'Collège',
            'description' => 'Description initiale.',
            'budget' => 100,
            'statut' => 'ouverte',
        ]);

        $response = $this->actingAs($this->apprenant)->patch(route('demandes.update', $demande), [
            'matiere' => 'Anglais Oral & Écrit',
            'niveau' => 'Lycée',
            'description' => 'Description mise à jour et détaillée.',
            'budget' => 140,
        ]);

        $response->assertRedirect(route('demandes.show', $demande));
        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'matiere' => 'Anglais Oral & Écrit',
            'niveau' => 'Lycée',
            'budget' => 140,
        ]);
    }

    /**
     * Test suppression d'une demande par son créateur.
     */
    public function test_apprenant_can_delete_own_demande(): void
    {
        $demande = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Histoire-Géo',
            'niveau' => 'Lycée',
            'description' => 'Demande à annuler.',
            'budget' => 80,
            'statut' => 'en_attente_moderation',
        ]);

        $response = $this->actingAs($this->apprenant)->delete(route('demandes.destroy', $demande));

        $response->assertRedirect(route('demandes.index'));
        $this->assertDatabaseMissing('demandes', [
            'id' => $demande->id,
        ]);
    }

    /**
     * Test clôture d'une demande en cours par l'apprenant.
     */
    public function test_apprenant_can_terminer_demande_in_progress(): void
    {
        $demande = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Philosophie',
            'niveau' => 'Baccalauréat',
            'description' => 'Cours terminés.',
            'budget' => 120,
            'statut' => 'en_cours',
        ]);

        $response = $this->actingAs($this->apprenant)->patch(route('demandes.terminer', $demande));

        $response->assertRedirect(route('demandes.show', $demande));
        $this->assertDatabaseHas('demandes', [
            'id' => $demande->id,
            'statut' => 'terminee',
        ]);
    }
}
