<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div>
                <h1 class="page-title text-2xl font-black text-white font-display">Tableau de bord</h1>
                <p class="page-subtitle text-xs text-slate-400 mt-0.5">Vue d'ensemble de votre activité</p>
            </div>
            <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-[#101726] border border-slate-800 text-slate-300 text-xs font-semibold shadow-sm">
                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ date('d F Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- SOFT WARM HERO BANNER --}}
        <div class="relative rounded-3xl p-7 sm:p-8 text-white overflow-hidden shadow-xl bg-gradient-to-r from-[#20180F] via-[#181C28] to-[#101726] border border-amber-500/25">
            <!-- Subtle background ambient warmth -->
            <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-amber-500/5 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-300 mb-3 border border-amber-500/20 backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        @if(Auth::user()->isAdmin()) Administrateur
                        @elseif(Auth::user()->isTuteur()) Tuteur
                        @else Apprenant @endif
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight font-display">
                        Bonjour, {{ Auth::user()->name }}
                    </h2>
                    <p class="text-slate-300 text-sm mt-1.5 max-w-xl font-normal leading-relaxed">
                        @if(Auth::user()->isApprenant())
                            Suivez vos demandes de cours et connectez-vous avec vos tuteurs certifiés.
                        @elseif(Auth::user()->isTuteur())
                            Consultez les demandes publiées, formulez vos propositions et développez votre activité.
                        @else
                            Supervisez les modérations et gérez les comptes de la plateforme.
                        @endif
                    </p>
                </div>

                <div class="flex-shrink-0">
                    @if(Auth::user()->isApprenant())
                        <a href="{{ route('demandes.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold bg-[#101726] text-white hover:bg-[#162035] transition shadow-lg border border-slate-700/50">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Nouvelle demande
                        </a>
                    @elseif(Auth::user()->isTuteur())
                        <a href="{{ route('demandes.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold bg-[#101726] text-white hover:bg-[#162035] transition shadow-lg border border-slate-700/50">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Explorer les demandes
                        </a>
                    @else
                        <a href="{{ route('admin.moderation.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold bg-[#101726] text-white hover:bg-[#162035] transition shadow-lg border border-slate-700/50">
                            Modérations en attente
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- 4 STAT CARDS (From Screenshot 4) --}}
        @if(Auth::user()->hasRole('admin'))
            @php
                $totalUsers    = \App\Models\User::count();
                $totalDemandes = \App\Models\Demande::count();
                $totalOffres   = \App\Models\Offre::count();
                $pendingMod    = \App\Models\Demande::whereIn('statut', ['en_attente_moderation', 'en_moderation'])->count();
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="stat-number">{{ $totalUsers }}</div>
                    <div class="stat-label">Utilisateurs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div class="stat-number">{{ $totalDemandes }}</div>
                    <div class="stat-label">Demandes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <div class="stat-number">{{ $totalOffres }}</div>
                    <div class="stat-label">Offres</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div class="stat-number">{{ $pendingMod }}</div>
                    <div class="stat-label">À modérer</div>
                </div>
            </div>

            {{-- ADMINISTRATION SECTION (From Screenshot 4) --}}
            <div class="card p-6">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 block">Administration</span>
                
                <div class="flex items-center gap-3 mb-6">
                    <a href="{{ route('admin.moderation.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold bg-amber-500 text-slate-950 shadow-md transition hover:brightness-105">
                        Modération ({{ $pendingMod }})
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-semibold bg-[#0B101B] border border-slate-800 text-slate-300 hover:text-white transition hover:border-slate-700">
                        Utilisateurs ({{ $totalUsers }})
                    </a>
                </div>

                @if($pendingMod === 0)
                    <div class="rounded-2xl border border-dashed border-slate-800 p-12 text-center bg-[#0B101B]/50">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-slate-800/80 border border-slate-700/80 flex items-center justify-center text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-white">Toutes les demandes ont été traitées</h3>
                        <p class="text-xs text-slate-400 mt-1">Aucune action de modération requise pour le moment.</p>
                    </div>
                @else
                    <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 p-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">{{ $pendingMod }} demande(s) en attente de modération</h4>
                                <p class="text-xs text-slate-400 mt-0.5">Vérifiez les demandes publiées par les apprenants.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.moderation.index') }}" class="btn-primary text-xs py-2">
                            Traiter maintenant &rarr;
                        </a>
                    </div>
                @endif
            </div>

        @elseif(Auth::user()->hasRole('apprenant'))
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div class="stat-number">{{ Auth::user()->demandes()->count() }}</div>
                    <div class="stat-label">Demandes publiées</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="stat-number">{{ Auth::user()->demandes()->where('statut', 'en_cours')->count() }}</div>
                    <div class="stat-label">En cours</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="stat-number">{{ Auth::user()->demandes()->where('statut', 'terminee')->count() }}</div>
                    <div class="stat-label">Terminées</div>
                </div>
            </div>

            @php $recentes = Auth::user()->demandes()->with(['apprenant'])->latest()->take(3)->get(); @endphp
            @if($recentes->count() > 0)
                <div class="card">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-sm font-bold text-white">Demandes récentes</h3>
                        <a href="{{ route('demandes.index') }}" class="text-xs font-bold text-amber-400 hover:text-amber-300">Voir tout &rarr;</a>
                    </div>
                    <div class="divide-y divide-slate-800">
                        @foreach($recentes as $demande)
                            @php
                                $statuts = [
                                    'en_attente_moderation' => ['bg-amber-500/10 text-amber-400 border border-amber-500/30', 'En modération'],
                                    'en_moderation'         => ['bg-amber-500/10 text-amber-400 border border-amber-500/30', 'En modération'],
                                    'ouverte'               => ['bg-emerald-500/10 text-emerald-400 border border-emerald-500/30', 'Ouverte'],
                                    'en_cours'              => ['bg-sky-500/10 text-sky-400 border border-sky-500/30', 'En cours'],
                                    'terminee'              => ['bg-slate-800 text-slate-300 border border-slate-700', 'Terminée'],
                                    'refusee'               => ['bg-red-500/10 text-red-400 border border-red-500/30', 'Refusée']
                                ];
                                [$badgeCls, $badgeLabel] = $statuts[$demande->statut] ?? ['bg-slate-800 text-slate-300', $demande->statut];
                            @endphp
                            <a href="{{ route('demandes.show', $demande) }}" class="flex items-center gap-4 py-3.5 px-3 rounded-xl transition hover:bg-white/[0.03] group no-underline">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-amber-500/10 text-amber-400 font-bold text-xs flex-shrink-0 border border-amber-500/20">
                                    {{ strtoupper(substr($demande->matiere, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-white text-sm truncate group-hover:text-amber-400 transition">{{ $demande->matiere }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ $demande->niveau }} &bull; {{ $demande->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeCls }}">{{ $badgeLabel }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="card p-10 text-center">
                    <p class="text-sm font-bold text-white">Aucune demande pour le moment</p>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Créez votre première demande pour recevoir des propositions de tuteurs qualifiés.</p>
                    <a href="{{ route('demandes.create') }}" class="btn-primary mt-4 inline-flex items-center gap-2 no-underline text-xs">
                        + Créer une demande
                    </a>
                </div>
            @endif

        @elseif(Auth::user()->hasRole('tuteur'))
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div class="stat-number">{{ Auth::user()->offres()->count() }}</div>
                    <div class="stat-label">Candidatures envoyées</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="stat-number">{{ Auth::user()->offres()->where('statut', 'acceptee')->count() }}</div>
                    <div class="stat-label">Acceptées</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="stat-number">{{ number_format(Auth::user()->offres()->where('statut', 'acceptee')->sum('tarif_propose'), 0) }} DH</div>
                    <div class="stat-label">Revenu total</div>
                </div>
            </div>

            <div class="card p-6">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 block">Accès rapide</span>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('demandes.index') }}" class="btn-primary no-underline text-xs">Explorer les demandes</a>
                    <a href="{{ route('offres.index') }}" class="btn-secondary no-underline text-xs">Mes candidatures</a>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
