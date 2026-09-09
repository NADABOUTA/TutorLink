<?php

namespace App\Policies;

use App\Models\Demande;
use App\Models\User;

class CommentairePolicy
{
    /**
     * Détermine si l'utilisateur peut publier un commentaire sur la demande.
     */
    public function create(User $user, Demande $demande): bool
    {
        return in_array($user->role, ['apprenant', 'tuteur', 'admin'], true);
    }
}
