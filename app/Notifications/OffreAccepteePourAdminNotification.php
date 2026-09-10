<?php

namespace App\Notifications;

use App\Models\Offre;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OffreAccepteePourAdminNotification extends Notification
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
        $apprenantName = $this->offre->demande->apprenant->name ?? 'Un apprenant';
        $tuteurName = $this->offre->tuteur->name ?? 'Un tuteur';
        $matiere = $this->offre->demande->matiere ?? 'cours';
        $tarif = number_format($this->offre->tarif_propose, 0);

        return [
            'type' => 'offre_acceptee_admin',
            'icon' => '🤝',
            'title' => 'Mise en relation confirmée',
            'message' => "{$apprenantName} a validé l'offre de {$tuteurName} pour le cours de {$matiere} ({$tarif} DH). Les coordonnées ont été partagées.",
            'url' => route('demandes.show', $this->offre->demande_id),
            'demande_id' => $this->offre->demande_id,
            'offre_id' => $this->offre->id,
        ];
    }
}
