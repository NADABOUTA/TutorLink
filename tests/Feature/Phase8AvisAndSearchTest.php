<?php

namespace Tests\Feature;

use App\Models\Avis;
use App\Models\Demande;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase8AvisAndSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_note_moyenne_accessor(): void
    {
        $apprenant = User::factory()->create();
        $apprenant->addRole('apprenant');

        $tuteur = User::factory()->create();
        $tuteur->addRole('tuteur');

        $demande1 = Demande::create([
            'apprenant_id' => $apprenant->id,
            'matiere' => 'Mathématiques',
            'niveau' => 'Lycée',
            'description' => 'Besoin d\'aide pour le bac',
            'budget' => 200,
            'statut' => 'terminee',
        ]);

        $demande2 = Demande::create([
            'apprenant_id' => $apprenant->id,
            'matiere' => 'Physique',
            'niveau' => 'Lycée',
            'description' => 'Besoin d\'aide pour la mécanique',
            'budget' => 250,
            'statut' => 'terminee',
        ]);

        // Avant avis : note moyenne est null
        $this->assertNull($tuteur->note_moyenne);

        // Avis 1 : 5/5
        Avis::create([
            'demande_id' => $demande1->id,
            'apprenant_id' => $apprenant->id,
            'tuteur_id' => $tuteur->id,
            'note' => 5,
            'commentaire' => 'Excellent tuteur, très clair !',
        ]);

        $tuteur->refresh();
        $this->assertEquals(5.0, $tuteur->note_moyenne);

        // Avis 2 : 4/5 -> Moyenne = 4.5
        Avis::create([
            'demande_id' => $demande2->id,
            'apprenant_id' => $apprenant->id,
            'tuteur_id' => $tuteur->id,
            'note' => 4,
            'commentaire' => 'Très bonne séance, ponctuel.',
        ]);

        $tuteur->refresh();
        $this->assertEquals(4.5, $tuteur->note_moyenne);
    }

    public function test_apprenant_can_submit_avis_for_accepted_offre(): void
    {
        $apprenant = User::factory()->create();
        $apprenant->addRole('apprenant');

        $tuteur = User::factory()->create();
        $tuteur->addRole('tuteur');

        $demande = Demande::create([
            'apprenant_id' => $apprenant->id,
            'matiere' => 'Anglais',
            'niveau' => 'Collège',
            'description' => 'Préparation brevet',
            'budget' => 150,
            'statut' => 'en_cours',
        ]);

        $offre = Offre::create([
            'demande_id' => $demande->id,
            'tuteur_id' => $tuteur->id,
            'tarif_propose' => 150,
            'message' => 'Je suis disponible dès lundi.',
            'statut' => 'acceptee',
            'coordonnees_visibles' => true,
        ]);

        $response = $this->actingAs($apprenant)->post(route('avis.store', $demande), [
            'note' => 5,
            'commentaire' => 'Professeur très pédagogue, mon niveau a nettement progressé.',
        ]);

        $response->assertRedirect(route('demandes.show', $demande));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('avis', [
            'demande_id' => $demande->id,
            'apprenant_id' => $apprenant->id,
            'tuteur_id' => $tuteur->id,
            'note' => 5,
        ]);

        $this->assertEquals('terminee', $demande->refresh()->statut);
    }

    public function test_apprenant_cannot_submit_duplicate_avis(): void
    {
        $apprenant = User::factory()->create();
        $apprenant->addRole('apprenant');

        $tuteur = User::factory()->create();
        $tuteur->addRole('tuteur');

        $demande = Demande::create([
            'apprenant_id' => $apprenant->id,
            'matiere' => 'SVT',
            'niveau' => 'Lycée',
            'description' => 'Génétique et immunologie',
            'budget' => 180,
            'statut' => 'terminee',
        ]);

        Offre::create([
            'demande_id' => $demande->id,
            'tuteur_id' => $tuteur->id,
            'tarif_propose' => 180,
            'message' => 'Offre tuteur',
            'statut' => 'acceptee',
        ]);

        Avis::create([
            'demande_id' => $demande->id,
            'apprenant_id' => $apprenant->id,
            'tuteur_id' => $tuteur->id,
            'note' => 5,
            'commentaire' => 'Premier avis',
        ]);

        $response = $this->actingAs($apprenant)->post(route('avis.store', $demande), [
            'note' => 4,
            'commentaire' => 'Tentative de doublon',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, Avis::where('demande_id', $demande->id)->count());
    }

    public function test_search_filters_by_matiere_and_niveau(): void
    {
        $tuteur = User::factory()->create();
        $tuteur->addRole('tuteur');

        $apprenant = User::factory()->create();
        $apprenant->addRole('apprenant');

        Demande::create([
            'apprenant_id' => $apprenant->id,
            'matiere' => 'Mathématiques',
            'niveau' => 'Lycée',
            'description' => 'Maths terminale',
            'budget' => 200,
            'statut' => 'ouverte',
        ]);

        Demande::create([
            'apprenant_id' => $apprenant->id,
            'matiere' => 'Français',
            'niveau' => 'Collège',
            'description' => 'Grammaire et rédaction',
            'budget' => 120,
            'statut' => 'ouverte',
        ]);

        // Filtrer par matière Maths
        $responseMaths = $this->actingAs($tuteur)->get(route('demandes.index', ['matiere' => 'Math']));
        $responseMaths->assertOk();
        $responseMaths->assertSee('Mathématiques');
        $responseMaths->assertDontSee('Grammaire et rédaction');

        // Filtrer par niveau Collège
        $responseCollege = $this->actingAs($tuteur)->get(route('demandes.index', ['niveau' => 'Collège']));
        $responseCollege->assertOk();
        $responseCollege->assertSee('Français');
        $responseCollege->assertDontSee('Maths terminale');
    }

    public function test_apprenant_can_mark_demande_as_terminee(): void
    {
        $apprenant = User::factory()->create();
        $apprenant->addRole('apprenant');

        $demande = Demande::create([
            'apprenant_id' => $apprenant->id,
            'matiere' => 'Chimie',
            'niveau' => 'Lycée',
            'description' => 'Chimie organique',
            'budget' => 160,
            'statut' => 'en_cours',
        ]);

        $response = $this->actingAs($apprenant)->patch(route('demandes.terminer', $demande));
        $response->assertRedirect(route('demandes.show', $demande));
        $response->assertSessionHas('success');

        $this->assertEquals('terminee', $demande->refresh()->statut);
    }
}
