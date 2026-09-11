<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Tableau de bord</h1>
                <p class="page-subtitle">Aperçu en temps réel de votre activité pédagogique</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="text-xs text-neutral-400 font-medium">{{ date('d F Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">

        {{-- HERO WELCOME CARD ÉDITORIALE (NOIR CHARCOAL & ALABASTER) --}}
        <div class="card p-6 sm:p-8 bg-[#121212] text-white border-none shadow-sm relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/10 text-neutral-300 mb-3 border border-white/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <span>
                            @if(Auth::user()->isAdmin()) Super Administrateur
                            @elseif(Auth::user()->isTuteur()) Tuteur Certifié
                            @else Apprenant Actif @endif
                        </span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight font-display">Bonjour, {{ Auth::user()->name }}</h2>
                    <p class="text-neutral-400 text-xs sm:text-sm mt-1.5 max-w-xl leading-relaxed font-normal">
                        @if(Auth::user()->isApprenant())
                            Suivez vos demandes de cours, comparez les propositions de tuteurs et échangez sans intermédiaire.
                        @elseif(Auth::user()->isTuteur())
                            Consultez les demandes des élèves, formulez vos propositions sur-mesure et planifiez vos séances.
                        @else
                            Supervisez les modérations de contenu, contrôlez la conformité des demandes et gérez les comptes membres.
                        @endif
                    </p>
                </div>

                <div class="flex-shrink-0 flex items-center gap-3">
                    @if(Auth::user()->isApprenant())
                        <a href="{{ route('demandes.create') }}" class="btn-primary !bg-white !text-neutral-900 hover:!bg-neutral-100 no-underline text-xs font-bold uppercase tracking-wider py-3 px-6">
                            + Nouvelle demande
                        </a>
                    @elseif(Auth::user()->isTuteur())
                        <a href="{{ route('demandes.index') }}" class="btn-primary !bg-white !text-neutral-900 hover:!bg-neutral-100 no-underline text-xs font-bold uppercase tracking-wider py-3 px-6">
                            Explorer les demandes
                        </a>
                    @else
                        <a href="{{ route('admin.moderation.index') }}" class="btn-primary !bg-white !text-neutral-900 hover:!bg-neutral-100 no-underline text-xs font-bold uppercase tracking-wider py-3 px-6">
                            Modérations en attente
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ROLE SPECIFIC STATS --}}
        @if(Auth::user()->hasRole('apprenant'))
            {{-- STATS APPRENANT --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="stat-card">
                    <div class="stat-number">{{ Auth::user()->demandes()->count() }}</div>
                    <div class="stat-label">Demandes Publiées</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number text-amber-600">{{ Auth::user()->demandes()->where('statut', 'en_cours')->count() }}</div>
                    <div class="stat-label">Séances En Cours</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number">{{ Auth::user()->demandes()->where('statut', 'terminee')->count() }}</div>
                    <div class="stat-label">Demandes Terminées</div>
                </div>
            </div>

            {{-- RECENT DEMANDES (APPRENANT) --}}
            @php $recentes = Auth::user()->demandes()->with(['apprenant'])->latest()->take(3)->get(); @endphp
            @if($recentes->count() > 0)
                <div class="card">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-base font-bold text-neutral-900 font-display">Demandes récentes</h3>
                            <p class="text-xs text-neutral-500">Vos dernières publications sur la plateforme</p>
                        </div>
                        <a href="{{ route('demandes.index') }}" class="text-xs font-bold text-neutral-900 hover:underline">Voir toutes mes demandes →</a>
                    </div>

                    <div class="divide-y divide-[#f5f5f4]">
                        @foreach($recentes as $demande)
                            @php
                                $statuts = [
                                    'en_attente_moderation' => ['badge-pending', 'En modération'],
                                    'en_moderation'         => ['badge-pending', 'En modération'],
                                    'ouverte'               => ['badge-success', 'Ouverte'],
                                    'en_cours'              => ['badge-info',    'En cours'],
                                    'terminee'              => ['badge-info',    'Terminée'],
                                    'refusee'               => ['badge-danger',  'Refusée']
                                ];
                                [$cls, $label] = $statuts[$demande->statut] ?? ['badge-info', $demande->statut];
                            @endphp
                            <a href="{{ route('demandes.show', $demande) }}" class="flex items-center gap-4 py-4 px-2 rounded-lg transition-colors hover:bg-[#fafaf9] group no-underline">
                                <div class="w-8 h-8 rounded-md flex items-center justify-center text-neutral-700 bg-neutral-100 font-bold text-xs flex-shrink-0">
                                    {{ substr($demande->matiere, 0, 2) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-neutral-900 text-sm truncate group-hover:text-black">{{ $demande->matiere }}</p>
                                    <p class="text-xs text-neutral-500 truncate">{{ $demande->niveau }} · Publiée {{ $demande->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="{{ $cls }}">{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="card p-10 text-center">
                    <p class="text-sm font-bold text-neutral-900 font-display">Aucune demande enregistrée</p>
                    <p class="text-xs text-neutral-500 mt-1 max-w-sm mx-auto">Créez votre première demande pour recevoir des propositions personnalisées.</p>
                    <a href="{{ route('demandes.create') }}" class="btn-primary mt-4 inline-flex items-center gap-2 no-underline">
                        + Créer ma première demande
                    </a>
                </div>
            @endif

        @elseif(Auth::user()->hasRole('tuteur'))
            {{-- STATS TUTEUR --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="stat-card">
                    <div class="stat-number">{{ Auth::user()->offres()->count() }}</div>
                    <div class="stat-label">Candidatures Envoyées</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number text-emerald-700">{{ Auth::user()->offres()->where('statut', 'acceptee')->count() }}</div>
                    <div class="stat-label">Candidatures Acceptées</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number font-display">{{ number_format(Auth::user()->offres()->where('statut', 'acceptee')->sum('tarif_propose'), 0) }} DH</div>
                    <div class="stat-label">Volume d'affaires direct</div>
                </div>
            </div>

            {{-- QUICK ACTIONS TUTEUR --}}
            <div class="card p-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-4">Accès rapide</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('demandes.index') }}" class="btn-primary no-underline">
                        Explorer les demandes d'élèves
                    </a>
                    <a href="{{ route('offres.index') }}" class="btn-secondary no-underline">
                        Gérer mes candidatures
                    </a>
                </div>
            </div>

        @elseif(Auth::user()->hasRole('admin'))
            {{-- STATS ADMIN --}}
            @php
                $totalUsers    = \App\Models\User::count();
                $totalDemandes = \App\Models\Demande::count();
                $totalOffres   = \App\Models\Offre::count();
                $pendingMod    = \App\Models\Demande::whereIn('statut', ['en_attente_moderation', 'en_moderation'])->count();
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="stat-card">
                    <div class="stat-number font-display">{{ $totalUsers }}</div>
                    <div class="stat-label">Utilisateurs</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number font-display">{{ $totalDemandes }}</div>
                    <div class="stat-label">Demandes Publiées</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number font-display">{{ $totalOffres }}</div>
                    <div class="stat-label">Offres Proposées</div>
                </div>

                <div class="stat-card">
                    <div class="stat-number text-amber-600 font-display">{{ $pendingMod }}</div>
                    <div class="stat-label">À Modérer</div>
                </div>
            </div>

            {{-- ADMIN ACTIONS --}}
            <div class="card p-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-4">Administration de la plateforme</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.moderation.index') }}" class="btn-primary no-underline">
                        Modération des annonces ({{ $pendingMod }})
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary no-underline">
                        Gestion des utilisateurs ({{ $totalUsers }})
                    </a>
                    <a href="{{ route('demandes.index') }}" class="btn-secondary no-underline">
                        Consulter les annonces
                    </a>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
