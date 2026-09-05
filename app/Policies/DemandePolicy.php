<?php

namespace App\Policies;

use App\Models\Demande;
use App\Models\User;

class DemandePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Demande $demande): bool
    {
        // L'apprenant propriétaire peut toujours voir sa demande
        if ($user->id === $demande->apprenant_id) {
            return true;
        }

        // L'administrateur a accès complet
        if ($user->hasRole('admin')) {
            return true;
        }

        // Un tuteur peut consulter une demande si elle a été approuvée (ouverte) ou en cours
        if ($user->hasRole('tuteur') && in_array($demande->statut, ['ouverte', 'en_cours'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('apprenant') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Demande $demande): bool
    {
        // Seul l'auteur peut modifier sa demande, tant qu'elle n'est pas déjà engagée ou terminée
        return $user->id === $demande->apprenant_id && in_array($demande->statut, ['en_attente_moderation', 'ouverte', 'refusee']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Demande $demande): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $demande->apprenant_id && in_array($demande->statut, ['en_attente_moderation', 'ouverte', 'refusee']);
    }
}
