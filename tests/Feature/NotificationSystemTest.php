<?php

namespace Tests\Feature;

use App\Models\Demande;
use App\Models\Offre;
use App\Models\Role;
use App\Models\User;
use App\Notifications\DemandeApprouveeNotification;
use App\Notifications\DemandeRejeteeNotification;
use App\Notifications\NouveauCommentaireNotification;
use App\Notifications\NouvelleOffreNotification;
use App\Notifications\OffreAccepteeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $apprenant;
    protected User $tuteur;
    protected User $admin;
    protected Demande $demande;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::firstOrCreate(['name' => 'tuteur'], ['display_name' => 'Tuteur']);
        Role::firstOrCreate(['name' => 'apprenant'], ['display_name' => 'Apprenant']);

        $this->apprenant = User::factory()->create(['name' => 'Sara Apprenante']);
        $this->apprenant->addRole('apprenant');

        $this->tuteur = User::factory()->create(['name' => 'Professeur Youssef']);
        $this->tuteur->addRole('tuteur');

        $this->admin = User::factory()->create(['name' => 'Admin']);
        $this->admin->addRole('admin');

        $this->demande = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Physique-Chimie',
            'niveau' => 'Baccalauréat',
            'description' => 'Préparation intensive pour le baccalauréat.',
            'budget' => 200,
            'statut' => 'ouverte',
        ]);
    }

    /**
     * Test notification de l'apprenant lorsqu'un tuteur soumet une offre.
     */
    public function test_student_receives_notification_when_tutor_submits_offer(): void
    {
        $response = $this->actingAs($this->tuteur)->post(route('demandes.offres.store', $this->demande), [
            'tarif_propose' => 180,
            'message' => 'Je suis disponible les week-ends pour assurer vos cours.',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertEquals(1, $this->apprenant->unreadNotifications->count());
        $notification = $this->apprenant->unreadNotifications->first();
        $this->assertEquals('nouvelle_offre', $notification->data['type']);
        $this->assertStringContainsString('Professeur Youssef', $notification->data['message']);
    }

    /**
     * Test notification du tuteur lorsque son offre est acceptée par l'apprenant.
     */
    public function test_tutor_receives_notification_when_offer_is_accepted(): void
    {
        $offre = Offre::create([
            'demande_id' => $this->demande->id,
            'tuteur_id' => $this->tuteur->id,
            'tarif_propose' => 180,
            'message' => 'Proposition de cours détaillés.',
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($this->apprenant)->patch(route('offres.accepter', $offre));
        $response->assertRedirect(route('demandes.show', $this->demande->id));

        $this->assertEquals(1, $this->tuteur->unreadNotifications->count());
        $notification = $this->tuteur->unreadNotifications->first();
        $this->assertEquals('offre_acceptee', $notification->data['type']);
        $this->assertStringContainsString('Sara Apprenante', $notification->data['message']);
    }

    /**
     * Test notification de l'apprenant lorsque l'admin approuve sa demande.
     */
    public function test_student_receives_notification_when_admin_approves_demande(): void
    {
        $demandeEnAttente = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Maths',
            'niveau' => 'Lycée',
            'description' => 'Besoin de soutien en géométrie.',
            'budget' => 150,
            'statut' => 'en_attente_moderation',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.moderation.approuver', $demandeEnAttente));
        $response->assertRedirect();

        $this->assertEquals(1, $this->apprenant->unreadNotifications->count());
        $notification = $this->apprenant->unreadNotifications->first();
        $this->assertEquals('demande_approuvee', $notification->data['type']);
    }

    /**
     * Test notification de l'apprenant lorsque l'admin refuse sa demande avec un motif.
     */
    public function test_student_receives_notification_when_admin_refuses_demande(): void
    {
        $demandeEnAttente = Demande::create([
            'apprenant_id' => $this->apprenant->id,
            'matiere' => 'Maths',
            'niveau' => 'Lycée',
            'description' => 'Demande avec description trop courte.',
            'budget' => 150,
            'statut' => 'en_attente_moderation',
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.moderation.refuser', $demandeEnAttente), [
            'motif_refus' => 'Description insuffisante pour les tuteurs.',
        ]);
        $response->assertRedirect();

        $this->assertEquals(1, $this->apprenant->unreadNotifications->count());
        $notification = $this->apprenant->unreadNotifications->first();
        $this->assertEquals('demande_rejetee', $notification->data['type']);
        $this->assertStringContainsString('Description insuffisante', $notification->data['message']);
    }

    /**
     * Test notification lors d'un nouveau commentaire dans la discussion.
     */
    public function test_participants_receive_notification_on_new_comment(): void
    {
        $response = $this->actingAs($this->tuteur)->post(route('demandes.commentaires.store', $this->demande), [
            'contenu' => 'Bonjour Sara, à quelle heure préférez-vous commencer les cours ?',
        ]);
        $response->assertRedirect();

        $this->assertEquals(1, $this->apprenant->unreadNotifications->count());
        $notification = $this->apprenant->unreadNotifications->first();
        $this->assertEquals('nouveau_commentaire', $notification->data['type']);
    }

    /**
     * Test consultation de la liste des notifications et marquage comme lu.
     */
    public function test_user_can_view_and_mark_notifications_as_read(): void
    {
        // Créer une notification factice pour l'apprenant
        $this->apprenant->notify(new DemandeApprouveeNotification($this->demande));
        $notification = $this->apprenant->unreadNotifications->first();

        // 1. Consultation de la page notifications
        $indexResp = $this->actingAs($this->apprenant)->get(route('notifications.index'));
        $indexResp->assertStatus(200);
        $indexResp->assertSee('Demande approuvée par la modération');

        // 2. Marquer une notification comme lue
        $readResp = $this->actingAs($this->apprenant)->patch(route('notifications.read', $notification->id));
        $readResp->assertRedirect(route('demandes.show', $this->demande->id));

        $this->assertEquals(0, $this->apprenant->fresh()->unreadNotifications->count());
    }

    /**
     * Test de l'action tout marquer comme lu.
     */
    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $this->apprenant->notify(new DemandeApprouveeNotification($this->demande));
        $this->apprenant->notify(new DemandeApprouveeNotification($this->demande));
        $this->assertEquals(2, $this->apprenant->unreadNotifications->count());

        $response = $this->actingAs($this->apprenant)->post(route('notifications.markAllRead'));
        $response->assertRedirect();

        $this->assertEquals(0, $this->apprenant->fresh()->unreadNotifications->count());
    }
}
