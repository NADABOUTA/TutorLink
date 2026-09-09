<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Demande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    /**
     * Enregistre un nouveau commentaire ou message de discussion sur une demande / offre.
     */
    public function store(Request $request, Demande $demande): RedirectResponse
    {
        $user = Auth::user();

        // Vérification par CommentairePolicy
        \Illuminate\Support\Facades\Gate::authorize('create', [\App\Models\Commentaire::class, $demande]);

        $validated = $request->validate([
            'contenu'  => ['required', 'string', 'min:2', 'max:1000'],
            'offre_id' => ['nullable', 'exists:offres,id'],
        ], [
            'contenu.required' => 'Veuillez saisir votre message.',
            'contenu.min'      => 'Votre message doit contenir au moins 2 caractères.',
            'contenu.max'      => 'Votre message ne peut pas dépasser 1000 caractères.',
        ]);

        $commentaire = Commentaire::create([
            'demande_id' => $demande->id,
            'user_id'    => $user->id,
            'offre_id'   => $validated['offre_id'] ?? null,
            'contenu'    => $validated['contenu'],
        ]);

        // Notifier les autres participants de la discussion
        if ($demande->apprenant_id !== $user->id) {
            $demande->apprenant->notify(new \App\Notifications\NouveauCommentaireNotification($commentaire));
        } else {
            // Si c'est l'apprenant qui répond, notifier les tuteurs ayant postulé
            foreach ($demande->offres as $offre) {
                if ($offre->tuteur_id !== $user->id) {
                    $offre->tuteur->notify(new \App\Notifications\NouveauCommentaireNotification($commentaire));
                }
            }
        }

        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès !');
    }
}
