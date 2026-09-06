<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Demande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    /**
     * Enregistre l'avis et la note (1-5) laissés par l'apprenant pour le tuteur sélectionné.
     */
    public function store(Request $request, Demande $demande): RedirectResponse
    {
        $user = Auth::user();

        // 1. Seul l'apprenant propriétaire de la demande peut déposer un avis
        if ($demande->apprenant_id !== $user->id) {
            abort(403, "Seul l'auteur de cette demande peut laisser une évaluation.");
        }

        // 2. Trouver l'offre acceptée pour cette demande
        $offreAcceptee = $demande->offres()->where('statut', 'acceptee')->first();
        if (!$offreAcceptee) {
            return redirect()->back()->with('error', "Impossible d'évaluer : aucune offre n'a été acceptée pour cette demande.");
        }

        // 3. Vérifier qu'un avis n'a pas déjà été publié pour cette demande
        if ($demande->avis()->exists()) {
            return redirect()->back()->with('error', 'Vous avez déjà évalué cette prestation.');
        }

        // 4. Validation des entrées
        $validated = $request->validate([
            'note' => ['required', 'integer', 'between:1,5'],
            'commentaire' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'note.required' => 'Veuillez attribuer une note entre 1 et 5 étoiles.',
            'note.between'  => 'La note doit être comprise entre 1 et 5 étoiles.',
            'commentaire.required' => 'Veuillez rédiger un commentaire sur votre expérience.',
            'commentaire.min'      => 'Le commentaire doit comporter au moins 5 caractères.',
            'commentaire.max'      => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ]);

        // 5. Enregistrer l'avis
        Avis::create([
            'demande_id'   => $demande->id,
            'apprenant_id' => $user->id,
            'tuteur_id'    => $offreAcceptee->tuteur_id,
            'note'         => (int) $validated['note'],
            'commentaire'  => $validated['commentaire'],
        ]);

        // 6. Si la demande était encore 'en_cours', on la clôture en 'terminee'
        if ($demande->statut !== 'terminee') {
            $demande->update(['statut' => 'terminee']);
        }

        return redirect()->route('demandes.show', $demande)
            ->with('success', 'Votre avis a été publié avec succès ! Merci pour votre retour.');
    }
}
