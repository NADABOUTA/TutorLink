<?php

namespace App\Notifications;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DemandeRejeteeNotification extends Notification
{
    use Queueable;

    public Demande $demande;
    public ?string $motif;

    public function __construct(Demande $demande, ?string $motif = null)
    {
        $this->demande = $demande;
        $this->motif = $motif;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $motifText = $this->motif ? " Motif : {$this->motif}" : "";

        return [
            'type' => 'demande_rejetee',
            'icon' => '⚠️',
            'title' => 'Demande non validée',
            'message' => "Votre demande de {$this->demande->matiere} n'a pas été retenue par la modération.{$motifText}",
            'url' => route('demandes.show', $this->demande->id),
            'demande_id' => $this->demande->id,
        ];
    }
}
