<?php

namespace App\Policies;

use App\Models\Demande;
use App\Models\Offre;
use App\Models\User;

class OffrePolicy
{
    /**
     * Détermine si l'utilisateur peut consulter ses offres.
     */
    public function viewAny(User $user): bool
    {
        return $user->isTuteur() || $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut soumettre une offre sur une demande.
     */
    public function create(User $user, Demande $demande): bool
    {
        // Seul un tuteur (ou admin) peut soumettre une offre
        if (!$user->isTuteur() && !$user->isAdmin()) {
            return false;
        }

        // Impossible de postuler sur sa propre demande
        if ($demande->apprenant_id === $user->id) {
            return false;
        }

        // La demande doit être validée et ouverte
        return $demande->statut === 'ouverte';
    }

    /**
     * Détermine si l'utilisateur peut accepter une offre reçue.
     */
    public function accepter(User $user, Offre $offre): bool
    {
        // L'administrateur a le droit universel
        if ($user->isAdmin()) {
            return true;
        }

        // Seul l'apprenant propriétaire de la demande peut accepter l'offre
        return $offre->demande->apprenant_id === $user->id;
    }
}
