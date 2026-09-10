<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemandeRequest;
use App\Models\Demande;
use App\Models\User;
use App\Notifications\NouvelleDemandePourAdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource with filters (zero N+1 queries).
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Demande::query();

        // Si l'utilisateur est apprenant, il consulte ses propres demandes
        if ($user->isApprenant() && ! $user->isAdmin()) {
            $query->where('apprenant_id', $user->id)
                ->with(['apprenant'])
                ->withCount('offres')
                ->latest();
        } else {
            // Si c'est un tuteur, il consulte les demandes ouvertes à la candidature
            $query->where('statut', 'ouverte')
                ->with(['apprenant'])
                ->withCount('offres')
                ->latest();
        }

        // Filtre par matière (recherche insensible)
        if ($request->filled('matiere')) {
            $query->where('matiere', 'like', '%'.$request->query('matiere').'%');
        }

        // Filtre par niveau
        if ($request->filled('niveau')) {
            $query->where('niveau', $request->query('niveau'));
        }

        // Filtre par statut (pour les apprenants)
        if ($request->filled('statut') && $user->isApprenant()) {
            $st = $request->query('statut');
            if ($st === 'en_moderation' || $st === 'en_attente_moderation') {
                $query->whereIn('statut', ['en_attente_moderation', 'en_moderation']);
            } else {
                $query->where('statut', $st);
            }
        }

        $demandes = $query->paginate(9)->withQueryString();

        // Listes pour les sélecteurs de filtre
        $niveauxDisponibles = [
            'Primaire',
            'Collège',
            'Lycée',
            'Baccalauréat',
            'Supérieur / Université',
            'Formation Professionnelle',
            'Autre',
        ];

        return view('demandes.index', compact('demandes', 'niveauxDisponibles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Demande::class);

        $niveaux = [
            'Primaire',
            'Collège',
            'Lycée',
            'Baccalauréat',
            'Supérieur / Université',
            'Formation Professionnelle',
            'Autre',
        ];

        return view('demandes.create', compact('niveaux'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DemandeRequest $request): RedirectResponse
    {
        Gate::authorize('create', Demande::class);

        $demande = $request->user()->demandes()->create([
            'matiere' => $request->validated('matiere'),
            'niveau' => $request->validated('niveau'),
            'description' => $request->validated('description'),
            'budget' => $request->validated('budget'),
            'statut' => 'en_attente_moderation',
        ]);

        // Notifier tous les administrateurs qu'une nouvelle demande est à modérer
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NouvelleDemandePourAdminNotification($demande));
        }

        return redirect()->route('demandes.index')
            ->with('success', 'Votre demande a été publiée avec succès ! Elle sera examinée par un modérateur avant d\'être visible aux tuteurs.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Demande $demande): View
    {
        Gate::authorize('view', $demande);

        // Eager loading pour éviter le N+1 sur les relations affichées
        $demande->load([
            'apprenant',
            'offres.tuteur.avisRecus.apprenant',
            'avis',
            'commentaires.user',
        ]);

        return view('demandes.show', compact('demande'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Demande $demande): View
    {
        Gate::authorize('update', $demande);

        $niveaux = [
            'Primaire',
            'Collège',
            'Lycée',
            'Baccalauréat',
            'Supérieur / Université',
            'Formation Professionnelle',
            'Autre',
        ];

        return view('demandes.edit', compact('demande', 'niveaux'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DemandeRequest $request, Demande $demande): RedirectResponse
    {
        Gate::authorize('update', $demande);

        $data = [
            'matiere' => $request->validated('matiere'),
            'niveau' => $request->validated('niveau'),
            'description' => $request->validated('description'),
            'budget' => $request->validated('budget'),
        ];

        // Si la demande avait été refusée, la modification la remet en modération
        if ($demande->statut === 'refusee') {
            $data['statut'] = 'en_attente_moderation';
            $data['motif_refus'] = null;
        }

        $demande->update($data);

        return redirect()->route('demandes.show', $demande)
            ->with('success', 'Votre demande a été mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demande $demande): RedirectResponse
    {
        Gate::authorize('delete', $demande);

        $demande->delete();

        return redirect()->route('demandes.index')
            ->with('success', 'La demande a été supprimée avec succès.');
    }

    /**
     * Marque la demande comme terminée par l'apprenant.
     */
    public function terminer(Demande $demande): RedirectResponse
    {
        Gate::authorize('terminer', $demande);

        if ($demande->statut !== 'en_cours') {
            return redirect()->back()->with('error', 'Seule une demande en cours peut être marquée comme terminée.');
        }

        $demande->update(['statut' => 'terminee']);

        return redirect()->route('demandes.show', $demande)
            ->with('success', 'La demande a été clôturée avec succès. Vous pouvez maintenant évaluer votre tuteur !');
    }
}
