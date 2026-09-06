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

        // Seul l'apprenant, un tuteur ou un admin peut participer à la discussion
        if (!$user->hasRole(['apprenant', 'tuteur', 'admin'])) {
            abort(403, "Action non autorisée.");
        }

        $validated = $request->validate([
            'contenu'  => ['required', 'string', 'min:2', 'max:1000'],
            'offre_id' => ['nullable', 'exists:offres,id'],
        ], [
            'contenu.required' => 'Veuillez saisir votre message.',
            'contenu.min'      => 'Votre message doit contenir au moins 2 caractères.',
            'contenu.max'      => 'Votre message ne peut pas dépasser 1000 caractères.',
        ]);

        Commentaire::create([
            'demande_id' => $demande->id,
            'user_id'    => $user->id,
            'offre_id'   => $validated['offre_id'] ?? null,
            'contenu'    => $validated['contenu'],
        ]);

        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès !');
    }
}
