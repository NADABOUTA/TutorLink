<?php

namespace App\Notifications;

use App\Models\Commentaire;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouveauCommentaireNotification extends Notification
{
    use Queueable;

    public Commentaire $commentaire;

    public function __construct(Commentaire $commentaire)
    {
        $this->commentaire = $commentaire;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'nouveau_commentaire',
            'icon' => '💬',
            'title' => 'Nouveau message dans la discussion',
            'message' => "{$this->commentaire->user->name} a écrit : \"" . \Illuminate\Support\Str::limit($this->commentaire->contenu, 60) . "\"",
            'url' => route('demandes.show', $this->commentaire->demande_id) . '#espace-commentaires',
            'demande_id' => $this->commentaire->demande_id,
        ];
    }
}
