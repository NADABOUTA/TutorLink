<?php

namespace Tests\Feature;

use App\Models\Commentaire;
use App\Models\Demande;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemandeWorkflowAndCommentairesTest extends TestCase
{
    use RefreshDatabase;

    public function test_demande_creation_starts_as_en_attente_moderation(): void
    {
        $apprenant = User::factory()->create();
        $apprenant->addRole('apprenant');

        $response = $this->actingAs($apprenant)->post(route('demandes.store'), [
            'matiere'     => 'Mathématiques',
            'niveau'      => 'Lycée',
            'budget'      => 200,
            'description' => 'Je recherche un tuteur pour m\'aider en analyse et probabilités.',
        ]);

        $response->assertRedirect(route('demandes.index'));
        $this->assertDatabaseHas('demandes', [
            'apprenant_id' => $apprenant->id,
            'matiere'      => 'Mathématiques',
            'statut'       => 'en_attente_moderation',
        ]);
    }

    public function test_tutor_only_sees_demande_after_admin_approval(): void
    {
        $admin = User::factory()->create();
        $admin->addRole('admin');

        $tuteur = User::factory()->create();
        $tuteur->addRole('tuteur');

        $apprenant = User::factory()->create();
        $apprenant->addRole('apprenant');

        $demande = Demande::create([
            'apprenant_id' => $apprenant->id,
            'matiere'      => 'Sciences Physiques',
            'niveau'       => 'Baccalauréat',
            'budget'       => 250,
            'description'  => 'Préparation intensive pour le baccalauréat national.',
            'statut'       => 'en_attente_moderation',
        ]);

        // 1. Avant approbation : le tuteur ne voit pas la demande
        $responseBefore = $this->actingAs($tuteur)->get(route('demandes.index'));
        $responseBefore->assertOk();
        $responseBefore->assertDontSee('Sciences Physiques');

        // 2. L'admin approuve la demande
        $responseAdmin = $this->actingAs($admin)->patch(route('admin.moderation.approuver', $demande));
        $responseAdmin->assertSessionHas('success');
        $this->assertEquals('ouverte', $demande->refresh()->statut);

        // 3. Après approbation : la demande apparaît pour le tuteur
        $responseAfter = $this->actingAs($tuteur)->get(route('demandes.index'));
        $responseAfter->assertOk();
        $responseAfter->assertSee('Sciences Physiques');
    }

    public function test_student_and_tutor_can_post_comments(): void
    {
        $apprenant = User::factory()->create(['name' => 'Yassine Apprenant']);
        $apprenant->addRole('apprenant');

        $tuteur = User::factory()->create(['name' => 'Professeur Rachid']);
        $tuteur->addRole('tuteur');

        $demande = Demande::create([
            'apprenant_id' => $apprenant->id,
            'matiere'      => 'Anglais',
            'niveau'       => 'Supérieur / Université',
            'budget'       => 300,
            'description'  => 'Préparation au TOEFL / IELTS.',
            'statut'       => 'ouverte',
        ]);

        // Message de l'apprenant
        $response1 = $this->actingAs($apprenant)->post(route('demandes.commentaires.store', $demande), [
            'contenu' => 'Bonjour, proposez-vous des cours le samedi matin ?',
        ]);
        $response1->assertSessionHas('success');

        // Réponse du tuteur
        $response2 = $this->actingAs($tuteur)->post(route('demandes.commentaires.store', $demande), [
            'contenu' => 'Bonjour Yassine, oui tout à fait, je suis disponible le samedi matin de 10h à 12h.',
        ]);
        $response2->assertSessionHas('success');

        $this->assertDatabaseCount('commentaires', 2);
        $this->assertEquals(2, $demande->commentaires()->count());

        // La vue affiche les messages
        $viewResponse = $this->actingAs($apprenant)->get(route('demandes.show', $demande));
        $viewResponse->assertOk();
        $viewResponse->assertSee('proposez-vous des cours le samedi matin');
        $viewResponse->assertSee('disponible le samedi matin de 10h à 12h');
    }

    public function test_accepting_offer_unlocks_contact_details(): void
    {
        $apprenant = User::factory()->create(['telephone' => '0612345678']);
        $apprenant->addRole('apprenant');

        $tuteur = User::factory()->create(['telephone' => '0698765432']);
        $tuteur->addRole('tuteur');

        $demande = Demande::create([
            'apprenant_id' => $apprenant->id,
            'matiere'      => 'Informatique',
            'niveau'       => 'Lycée',
            'budget'       => 200,
            'description'  => 'Apprentissage de Python et algorithmique.',
            'statut'       => 'ouverte',
        ]);

        $offre = Offre::create([
            'demande_id'           => $demande->id,
            'tuteur_id'            => $tuteur->id,
            'tarif_propose'        => 200,
            'message'              => 'Je suis ingénieur et formateur certifié Python.',
            'statut'               => 'en_attente',
            'coordonnees_visibles' => false,
        ]);

        // L'apprenant accepte l'offre
        $response = $this->actingAs($apprenant)->patch(route('offres.accepter', $offre));
        $response->assertRedirect(route('demandes.show', $demande));

        $offre->refresh();
        $this->assertEquals('acceptee', $offre->statut);
        $this->assertEquals('en_cours', $demande->refresh()->statut);
    }
}
