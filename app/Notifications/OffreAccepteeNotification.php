<?php

namespace App\Notifications;

use App\Models\Offre;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OffreAccepteeNotification extends Notification
{
    use Queueable;

    public Offre $offre;

    public function __construct(Offre $offre)
    {
        $this->offre = $offre;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'offre_acceptee',
            'icon' => '🎉',
            'title' => 'Votre offre a été acceptée !',
            'message' => "Félicitations ! {$this->offre->demande->apprenant->name} a retenu votre proposition pour {$this->offre->demande->matiere}. Les coordonnées de contact sont débloquées.",
            'url' => route('demandes.show', $this->offre->demande_id),
            'demande_id' => $this->offre->demande_id,
            'offre_id' => $this->offre->id,
        ];
    }
}
