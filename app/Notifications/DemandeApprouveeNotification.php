<?php

namespace App\Notifications;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DemandeApprouveeNotification extends Notification
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
            'type' => 'demande_approuvee',
            'icon' => '✅',
            'title' => 'Demande approuvée par la modération',
            'message' => "Votre demande de {$this->demande->matiere} ({$this->demande->niveau}) a été validée et est désormais visible aux tuteurs.",
            'url' => route('demandes.show', $this->demande->id),
            'demande_id' => $this->demande->id,
        ];
    }
}
