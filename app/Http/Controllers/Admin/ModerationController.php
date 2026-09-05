<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RefusDemandeRequest;
use App\Models\Demande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModerationController extends Controller
{
    /**
     * Display a listing of demandes pending moderation or with filters (zero N+1).
     */
    public function index(Request $request): View
    {
        $status = $request->query('statut', 'en_attente_moderation');

        $query = Demande::with(['apprenant'])
            ->withCount('offres')
            ->latest();

        if ($status !== 'all') {
            $query->where('statut', $status);
        }

        $demandes = $query->paginate(10)->withQueryString();

        // Statistiques de modération pour les onglets
        $counts = [
            'en_attente_moderation' => Demande::where('statut', 'en_attente_moderation')->count(),
            'ouverte' => Demande::where('statut', 'ouverte')->count(),
            'refusee' => Demande::where('statut', 'refusee')->count(),
            'all' => Demande::count(),
        ];

        return view('admin.moderation.index', compact('demandes', 'counts', 'status'));
    }

    /**
     * Approuve la demande et la rend ouverte aux tuteurs.
     */
    public function approuver(Demande $demande): RedirectResponse
    {
        $demande->update([
            'statut' => 'ouverte',
            'motif_refus' => null,
        ]);

        return redirect()->back()
            ->with('success', "La demande #{$demande->id} ({$demande->matiere} - {$demande->niveau}) a été approuvée. Elle est maintenant visible par les tuteurs.");
    }

    /**
     * Refuse la demande avec un motif explicatif pour l'apprenant.
     */
    public function refuser(RefusDemandeRequest $request, Demande $demande): RedirectResponse
    {
        $demande->update([
            'statut' => 'refusee',
            'motif_refus' => $request->validated('motif_refus'),
        ]);

        return redirect()->back()
            ->with('success', "La demande #{$demande->id} a été refusée avec succès. L'apprenant a été notifié du motif de rejet.");
    }
}
