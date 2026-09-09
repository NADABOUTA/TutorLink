<?php

namespace Tests\Feature;

use App\Models\Demande;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OffreCrudAndAcceptationTest extends TestCase
{
    use RefreshDatabase;

    protected User $apprenant;
    protected User $tuteur1;
    protected User $tuteur2;
    protected Demande $demande;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apprenant = User::factory()->create([
            'name' => 'Sara Apprenante',
            'telephone' => '0612345678',
            'email' => 'sara@example.com',
        ]);
        $this->apprenant->addRole('apprenant');

        $this->tuteur1 = User::factory()->create([
            'name' => 'Youssef Math',
            'telephone' => '0677889900',
            'email' => 'youssef@example.com',
            'matiere' => 'Mathématiques',
            'tarif_horaire' => 150,
            'bio' => 'Enseignant expérimenté en classes préparatoires.',
        ]);
        $this->tuteur1->addRole('tuteur');

        $this->tuteur2 = User::factory()->create([
            'name' => 'Fatima Tuteur',
            'telephone' => '0644332211',
            'email' => 'fatima@example.com',
        ]);
        $this->tuteur2->addRole('tuteur');

        $this->demande = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Mathématiques Analyse',
            'niveau' => 'Baccalauréat',
            'description' => 'Besoin d\'un soutien hebdomadaire intensif.',
            'budget' => 150,
            'statut' => 'ouverte',
        ]);
    }

    /**
     * Test soumission d'une offre par un tuteur sur une demande ouverte.
     */
    public function test_tuteur_can_submit_offre_on_open_demande(): void
    {
        $response = $this->actingAs($this->tuteur1)->post(route('demandes.offres.store', $this->demande), [
            'tarif_propose' => 140,
            'message' => 'Je suis disponible les mercredis et samedis pour vous accompagner.',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('offres', [
            'demande_id' => $this->demande->id,
            'tuteur_id' => $this->tuteur1->id,
            'tarif_propose' => 140,
            'statut' => 'en_attente',
        ]);
    }

    /**
     * Test rejet d'une offre si la demande n'est pas ouverte (ex: en modération).
     */
    public function test_tuteur_cannot_submit_offre_on_demande_in_moderation(): void
    {
        $demandeEnModeration = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Chimie',
            'niveau' => 'Lycée',
            'description' => 'En attente de modérateur.',
            'budget' => 100,
            'statut' => 'en_attente_moderation',
        ]);

        $response = $this->actingAs($this->tuteur1)->post(route('demandes.offres.store', $demandeEnModeration), [
            'tarif_propose' => 90,
            'message' => 'Je suis disponible et très intéressé par votre annonce.',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('offres', [
            'demande_id' => $demandeEnModeration->id,
            'tuteur_id' => $this->tuteur1->id,
        ]);
    }

    /**
     * Test interdiction pour l'apprenant de postuler à sa propre demande (ou à toute demande).
     */
    public function test_apprenant_cannot_submit_offre_on_own_demande(): void
    {
        $response = $this->actingAs($this->apprenant)->post(route('demandes.offres.store', $this->demande), [
            'tarif_propose' => 100,
            'message' => 'Tentative non autorisée de candidature par un apprenant.',
        ]);

        // Rejeté par la politique/autorisation de la requête OffreRequest (403)
        $response->assertStatus(403);
    }

    /**
     * Test consultation de la liste de ses offres par le tuteur (/offres).
     */
    public function test_tuteur_can_view_sent_offres_list(): void
    {
        Offre::create([
            'demande_id' => $this->demande->id,
            'tuteur_id' => $this->tuteur1->id,
            'tarif_propose' => 150,
            'message' => 'Proposition de cours avec message de plus de 15 caractères.',
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($this->tuteur1)->get('/offres');
        $response->assertStatus(200);
        $response->assertSee('Mathématiques Analyse');
        $response->assertSee('150');
    }

    /**
     * Test acceptation de l'offre :
     * - Offre retenue passe à 'acceptee' et coordonnees_visibles = true
     * - Autres offres passent à 'refusee'
     * - Demande passe à 'en_cours'
     * - Les boutons de contact mutuels (WhatsApp, Appel, Email) sont immédiatement débloqués.
     */
    public function test_apprenant_can_accept_offre_which_unlocks_contact_details_and_refuses_others(): void
    {
        $offreGagnante = Offre::create([
            'demande_id' => $this->demande->id,
            'tuteur_id' => $this->tuteur1->id,
            'tarif_propose' => 140,
            'message' => 'Offre sélectionnée avec explications détaillées.',
            'statut' => 'en_attente',
            'coordonnees_visibles' => false,
        ]);

        $offrePerdante = Offre::create([
            'demande_id' => $this->demande->id,
            'tuteur_id' => $this->tuteur2->id,
            'tarif_propose' => 160,
            'message' => 'Autre proposition de soutien scolaire.',
            'statut' => 'en_attente',
            'coordonnees_visibles' => false,
        ]);

        // Acceptation par l'apprenant
        $response = $this->actingAs($this->apprenant)->patch(route('offres.accepter', $offreGagnante));
        $response->assertRedirect(route('demandes.show', $this->demande->id));

        // Vérification de la transaction atomique en base de données
        $this->assertDatabaseHas('offres', [
            'id' => $offreGagnante->id,
            'statut' => 'acceptee',
            'coordonnees_visibles' => 1,
        ]);

        $this->assertDatabaseHas('offres', [
            'id' => $offrePerdante->id,
            'statut' => 'refusee',
        ]);

        $this->assertDatabaseHas('demandes', [
            'id' => $this->demande->id,
            'statut' => 'en_cours',
        ]);

        // Vérification du déblocage visuel des coordonnées sur la page
        $showResponse = $this->actingAs($this->apprenant)->get(route('demandes.show', $this->demande));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Offre acceptée');
        $showResponse->assertSee('Coordonnées de contact débloquées');
        $showResponse->assertSee('youssef@example.com');
        $showResponse->assertSee('wa.me/212677889900');
        $showResponse->assertSee('tel:0677889900');
    }
}
