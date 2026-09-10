<?php

namespace App\Notifications;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleDemandePourAdminNotification extends Notification
{
    use Queueable;

    public Demande $demande;

    public function __construct(Demande $demande)
    {
        $this->demande = $demande;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'demande_a_moderer',
            'icon' => '📋',
            'title' => 'Nouvelle demande à modérer',
            'message' => "L'apprenant {$this->demande->apprenant->name} a publié une demande en {$this->demande->matiere} ({$this->demande->niveau}) en attente de votre approbation.",
            'url' => route('admin.moderation.index'),
            'demande_id' => $this->demande->id,
        ];
    }
}
