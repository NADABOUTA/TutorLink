<?php

namespace App\Notifications;

use App\Models\Offre;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleOffreNotification extends Notification
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
            'type' => 'nouvelle_offre',
            'icon' => '📩',
            'title' => 'Nouvelle proposition reçue',
            'message' => "{$this->offre->tuteur->name} vous propose un accompagnement à " . number_format($this->offre->tarif_propose, 0) . " DH pour votre demande de {$this->offre->demande->matiere}.",
            'url' => route('demandes.show', $this->offre->demande_id),
            'demande_id' => $this->offre->demande_id,
            'offre_id' => $this->offre->id,
        ];
    }
}
