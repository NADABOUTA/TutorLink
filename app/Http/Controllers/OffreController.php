<?php

namespace App\Http\Controllers;

use App\Events\OffreAcceptee;
use App\Http\Requests\OffreRequest;
use App\Models\Demande;
use App\Models\Offre;
use App\Models\User;
use App\Notifications\NouvelleOffreNotification;
use App\Notifications\OffreAccepteeNotification;
use App\Notifications\OffreAccepteePourAdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OffreController extends Controller
{
    /**
     * Affiche la liste des offres envoyées par le tuteur connecté (Zero N+1).
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Offre::where('tuteur_id', $user->id)
            ->with(['demande.apprenant'])
            ->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->query('statut'));
        }

        $offres = $query->paginate(9)->withQueryString();

        $counts = [
            'all' => Offre::where('tuteur_id', $user->id)->count(),
            'en_attente' => Offre::where('tuteur_id', $user->id)->where('statut', 'en_attente')->count(),
            'acceptee' => Offre::where('tuteur_id', $user->id)->where('statut', 'acceptee')->count(),
            'refusee' => Offre::where('tuteur_id', $user->id)->where('statut', 'refusee')->count(),
        ];

        return view('offres.index', compact('offres', 'counts'));
    }

    /**
     * Enregistre une nouvelle offre d'un tuteur pour une demande ouverte.
     */
    public function store(OffreRequest $request, Demande $demande): RedirectResponse
    {
        $user = $request->user();

        // 1. Vérifier que la demande est ouverte
        if ($demande->statut !== 'ouverte') {
            return redirect()->back()->with('error', 'Cette demande n\'est plus ouverte aux candidatures.');
        }

        // 2. Empêcher l'apprenant de candidater à sa propre demande
        if ($demande->apprenant_id === $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas postuler à votre propre demande.');
        }

        // 3. Vérifier que le tuteur n'a pas déjà soumis une offre
        $dejaPostule = Offre::where('demande_id', $demande->id)
            ->where('tuteur_id', $user->id)
            ->exists();

        if ($dejaPostule) {
            return redirect()->back()->with('error', 'Vous avez déjà soumis une offre pour cette demande.');
        }

        // 4. Création de l'offre
        $offre = $demande->offres()->create([
            'tuteur_id' => $user->id,
            'tarif_propose' => $request->validated('tarif_propose'),
            'message' => $request->validated('message'),
            'statut' => 'en_attente',
            'coordonnees_visibles' => false,
        ]);

        // Notifier l'apprenant de la nouvelle offre
        $demande->apprenant->notify(new NouvelleOffreNotification($offre));

        return redirect()->route('demandes.show', $demande)
            ->with('success', 'Votre proposition d\'offre a été transmise à l\'apprenant avec succès !');
    }

    /**
     * Accepte une offre par l'apprenant propriétaire de la demande.
     */
    public function accepter(Request $request, Offre $offre): RedirectResponse
    {
        $user = $request->user();

        // Vérifier que c'est bien l'apprenant créateur de la demande via la Policy native
        Gate::authorize('accepter', $offre);

        // Vérifier que l'offre est toujours en attente
        if ($offre->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette offre a déjà été traitée.');
        }

        // Transaction SQL atomique
        DB::transaction(function () use ($offre) {
            // 1. Passer cette offre à acceptée et débloquer les coordonnées de contact immédiatement
            $offre->update([
                'statut' => 'acceptee',
                'coordonnees_visibles' => true,
            ]);

            // 2. Passer toutes les autres offres de cette même demande à refusée
            Offre::where('demande_id', $offre->demande_id)
                ->where('id', '!=', $offre->id)
                ->update(['statut' => 'refusee']);

            // 3. Passer la demande au statut en_cours
            $offre->demande()->update([
                'statut' => 'en_cours',
            ]);
        });

        // 4. Notifier le tuteur dont l'offre a été retenue
        $offre->tuteur->notify(new OffreAccepteeNotification($offre));

        // 4.bis Notifier les administrateurs de la mise en relation acceptée
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OffreAccepteePourAdminNotification($offre));
        }

        // 5. Déclencher l'événement OffreAcceptee
        event(new OffreAcceptee($offre));

        return redirect()->route('demandes.show', $offre->demande_id)
            ->with('success', "Vous avez accepté l'offre de {$offre->tuteur->name} ! La demande est désormais en cours et vos coordonnées de contact mutuelles sont partagées.");
    }
}
