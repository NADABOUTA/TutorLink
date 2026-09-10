<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Tableau de bord</h1>
                <p class="page-subtitle">Aperçu en temps réel de votre activité pédagogique</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="text-xs text-slate-400 font-semibold">{{ date('d F Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">

        {{-- HERO WELCOME CARD --}}
        <div class="card p-6 sm:p-8 bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white border-none shadow-lg relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-blue-200 backdrop-blur-sm border border-white/10 mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>
                            @if(Auth::user()->isAdmin()) Super Administrateur
                            @elseif(Auth::user()->isTuteur()) Tuteur Certifié
                            @else Apprenant Actif @endif
                        </span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Bonjour, {{ Auth::user()->name }} 👋</h2>
                    <p class="text-slate-300 text-sm mt-1.5 max-w-xl leading-relaxed">
                        @if(Auth::user()->isApprenant())
                            Consultez vos demandes de cours en cours, comparez les propositions de tuteurs et échangez sans intermédiaire.
                        @elseif(Auth::user()->isTuteur())
                            Accédez aux dernières demandes d'élèves, proposez votre accompagnement sur-mesure et planifiez vos séances.
                        @else
                            Supervisez les modérations de contenu, contrôlez la conformité des demandes et gérez les comptes membres.
                        @endif
                    </p>
                </div>

                <div class="flex-shrink-0 flex items-center gap-3">
                    @if(Auth::user()->isApprenant())
                        <a href="{{ route('demandes.create') }}" class="btn-primary py-3 px-6 shadow-lg no-underline font-bold text-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Nouvelle demande
                        </a>
                    @elseif(Auth::user()->isTuteur())
                        <a href="{{ route('demandes.index') }}" class="btn-primary py-3 px-6 shadow-lg no-underline font-bold text-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Explorer les demandes
                        </a>
                    @else
                        <a href="{{ route('admin.moderation.index') }}" class="btn-warning py-3 px-6 shadow-lg no-underline font-bold text-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Modération en attente
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
                    <div class="stat-icon bg-blue-50 text-blue-600 border border-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="stat-number">{{ Auth::user()->demandes()->count() }}</div>
                    <div class="stat-label">Demandes Publiées</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-amber-50 text-amber-600 border border-amber-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-number text-amber-600">{{ Auth::user()->demandes()->where('statut', 'en_cours')->count() }}</div>
                    <div class="stat-label">Séances En Cours</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-number text-emerald-600">{{ Auth::user()->demandes()->where('statut', 'terminee')->count() }}</div>
                    <div class="stat-label">Demandes Terminées</div>
                </div>
            </div>

            {{-- RECENT DEMANDES (APPRENANT) --}}
            @php $recentes = Auth::user()->demandes()->with(['apprenant'])->latest()->take(3)->get(); @endphp
            @if($recentes->count() > 0)
                <div class="card">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Demandes récentes</h3>
                            <p class="text-xs text-slate-500">Vos dernières publications sur la plateforme</p>
                        </div>
                        <a href="{{ route('demandes.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Voir toutes mes demandes →</a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach($recentes as $demande)
                            @php
                                $statuts = [
                                    'en_attente_moderation' => ['badge-pending', 'En modération'],
                                    'en_moderation'         => ['badge-pending', 'En modération'],
                                    'ouverte'               => ['badge-success', 'Ouverte'],
                                    'en_cours'              => ['badge-info',    'En cours'],
                                    'terminee'              => ['badge-purple',  'Terminée'],
                                    'refusee'               => ['badge-danger',  'Refusée']
                                ];
                                [$cls, $label] = $statuts[$demande->statut] ?? ['badge-info', $demande->statut];
                            @endphp
                            <a href="{{ route('demandes.show', $demande) }}" class="flex items-center gap-4 py-4 px-2 rounded-xl transition-colors hover:bg-slate-50 group no-underline">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-blue-600 bg-blue-50 font-bold flex-shrink-0 group-hover:bg-blue-100 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-slate-900 truncate group-hover:text-blue-600 transition-colors">{{ $demande->matiere }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $demande->niveau }} · Publiée {{ $demande->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="{{ $cls }}">{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="card p-10 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Aucune demande enregistrée</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Créez votre première demande pour recevoir rapidement des propositions personnalisées.</p>
                    <a href="{{ route('demandes.create') }}" class="btn-primary mt-4 inline-flex items-center gap-2 no-underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Créer ma première demande
                    </a>
                </div>
            @endif

        @elseif(Auth::user()->hasRole('tuteur'))
            {{-- STATS TUTEUR --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="stat-card">
                    <div class="stat-icon bg-blue-50 text-blue-600 border border-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div class="stat-number">{{ Auth::user()->offres()->count() }}</div>
                    <div class="stat-label">Candidatures Envoyées</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-number text-emerald-600">{{ Auth::user()->offres()->where('statut', 'acceptee')->count() }}</div>
                    <div class="stat-label">Candidatures Acceptées</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-amber-50 text-amber-600 border border-amber-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-number text-slate-900">{{ number_format(Auth::user()->offres()->where('statut', 'acceptee')->sum('tarif_propose'), 0) }} DH</div>
                    <div class="stat-label">Volume d'affaires direct</div>
                </div>
            </div>

            {{-- QUICK ACTIONS TUTEUR --}}
            <div class="card p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Accès rapide</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('demandes.index') }}" class="btn-primary no-underline">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Explorer les demandes d'élèves
                    </a>
                    <a href="{{ route('offres.index') }}" class="btn-secondary no-underline">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
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
                    <div class="stat-icon bg-blue-50 text-blue-600 border border-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="stat-number">{{ $totalUsers }}</div>
                    <div class="stat-label">Utilisateurs Enregistrés</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-indigo-50 text-indigo-600 border border-indigo-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="stat-number text-indigo-700">{{ $totalDemandes }}</div>
                    <div class="stat-label">Demandes Publiées</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <div class="stat-number text-emerald-600">{{ $totalOffres }}</div>
                    <div class="stat-label">Offres Proposées</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon bg-amber-50 text-amber-600 border border-amber-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="stat-number text-amber-600">{{ $pendingMod }}</div>
                    <div class="stat-label">À Modérer</div>
                </div>
            </div>

            {{-- ADMIN ACTIONS --}}
            <div class="card p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4">Administration de la plateforme</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.moderation.index') }}" class="btn-warning no-underline">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Modération des annonces ({{ $pendingMod }})
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn-primary no-underline">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Gestion des utilisateurs ({{ $totalUsers }})
                    </a>
                    <a href="{{ route('demandes.index') }}" class="btn-secondary no-underline">
                        Consulter toutes les annonces
                    </a>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
