<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Bienvenue, {{ Auth::user()->name }} 👋</p>
        </div>
    </x-slot>

    <div class="stagger-children">

        {{-- Stats Grid --}}
        @if(Auth::user()->hasRole('apprenant'))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
            <div class="stat-card indigo">
                <div class="stat-icon" style="background: rgba(99,102,241,0.15);">
                    <svg class="w-5 h-5" style="color: rgb(99,102,241);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ Auth::user()->demandes()->count() }}</div>
                <div class="stat-label">Demandes créées</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon" style="background: rgba(168,85,247,0.15);">
                    <svg class="w-5 h-5" style="color: rgb(168,85,247);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ Auth::user()->demandes()->where('statut', 'en_cours')->count() }}</div>
                <div class="stat-label">Sessions en cours</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon" style="background: rgba(34,197,94,0.15);">
                    <svg class="w-5 h-5" style="color: rgb(34,197,94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="stat-number">{{ Auth::user()->demandes()->where('statut', 'terminee')->count() }}</div>
                <div class="stat-label">Terminées</div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card mb-8">
            <h2 class="text-lg font-semibold text-white mb-4">Actions rapides</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('demandes.create') }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouvelle demande
                </a>
                <a href="{{ route('demandes.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Voir mes demandes
                </a>
            </div>
        </div>

        {{-- Recent Demandes --}}
        @php $recentes = Auth::user()->demandes()->with(['apprenant'])->latest()->take(3)->get(); @endphp
        @if($recentes->count() > 0)
        <div class="card">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-semibold text-white">Mes demandes récentes</h2>
                <a href="{{ route('demandes.index') }}" class="text-sm hover:underline" style="color: rgb(99,102,241);">Voir tout →</a>
            </div>
            <div class="space-y-3">
                @foreach($recentes as $demande)
                <a href="{{ route('demandes.show', $demande) }}" style="text-decoration:none;" class="flex items-center gap-4 p-4 rounded-xl transition-all duration-200 hover:bg-white/5" style="border: 1px solid rgba(255,255,255,0.05);">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0" style="background: rgba(99,102,241,0.15);">📚</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-white truncate">{{ $demande->matiere }}</p>
                        <p class="text-xs truncate" style="color: rgb(148,163,184);">{{ $demande->niveau }} — {{ $demande->created_at->diffForHumans() }}</p>
                    </div>
                    @php
                        $statuts = ['en_moderation'=>['badge-pending','En modération'],'ouverte'=>['badge-success','Ouverte'],'en_cours'=>['badge-info','En cours'],'terminee'=>['badge-purple','Terminée'],'refusee'=>['badge-danger','Refusée']];
                        [$cls, $label] = $statuts[$demande->statut] ?? ['badge-info', $demande->statut];
                    @endphp
                    <span class="{{ $cls }}">{{ $label }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <h3 class="empty-state-title">Aucune demande pour l'instant</h3>
                <p class="empty-state-desc">Créez votre première demande pour trouver un tuteur</p>
                <a href="{{ route('demandes.create') }}" class="btn-primary">Créer une demande</a>
            </div>
        </div>
        @endif

        @elseif(Auth::user()->hasRole('tuteur'))
        {{-- Dashboard Tuteur --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
            <div class="stat-card indigo">
                <div class="stat-icon" style="background: rgba(99,102,241,0.15);">
                    <svg class="w-5 h-5" style="color: rgb(99,102,241);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ Auth::user()->offres()->count() }}</div>
                <div class="stat-label">Offres envoyées</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon" style="background: rgba(34,197,94,0.15);">
                    <svg class="w-5 h-5" style="color: rgb(34,197,94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ Auth::user()->offres()->where('statut', 'acceptee')->count() }}</div>
                <div class="stat-label">Offres acceptées</div>
            </div>
            <div class="stat-card pink">
                <div class="stat-icon" style="background: rgba(236,72,153,0.15);">
                    <svg class="w-5 h-5" style="color: rgb(236,72,153);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-number">{{ Auth::user()->offres()->where('statut', 'acceptee')->sum('tarif_propose') }} €</div>
                <div class="stat-label">Revenus potentiels</div>
            </div>
        </div>

        <div class="card mb-8">
            <h2 class="text-lg font-semibold text-white mb-4">Actions rapides</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('demandes.index') }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Parcourir les demandes
                </a>
                <a href="{{ route('offres.index') }}" class="btn-secondary">Mes offres</a>
            </div>
        </div>

        @elseif(Auth::user()->hasRole('admin'))
        {{-- Dashboard Admin --}}
        @php
            $totalUsers    = \App\Models\User::count();
            $totalDemandes = \App\Models\Demande::count();
            $totalOffres   = \App\Models\Offre::count();
            $pendingMod    = \App\Models\Demande::whereIn('statut', ['en_attente_moderation', 'en_moderation'])->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="stat-card indigo">
                <div class="stat-icon" style="background: rgba(99,102,241,0.15);">
                    <svg class="w-5 h-5" style="color:rgb(99,102,241);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-label">Utilisateurs</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon" style="background: rgba(168,85,247,0.15);">
                    <svg class="w-5 h-5" style="color:rgb(168,85,247);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="stat-number">{{ $totalDemandes }}</div>
                <div class="stat-label">Demandes</div>
            </div>
            <div class="stat-card pink">
                <div class="stat-icon" style="background: rgba(236,72,153,0.15);">
                    <svg class="w-5 h-5" style="color:rgb(236,72,153);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="stat-number">{{ $totalOffres }}</div>
                <div class="stat-label">Offres</div>
            </div>
            <div class="stat-card" style="background: rgba(234,179,8,0.08); border-color: rgba(234,179,8,0.2);">
                <div class="stat-icon" style="background: rgba(234,179,8,0.15);">
                    <svg class="w-5 h-5" style="color:rgb(234,179,8);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="stat-number" style="color:rgb(234,179,8);">{{ $pendingMod }}</div>
                <div class="stat-label">En attente de modération</div>
            </div>
        </div>

        <div class="card">
            <h2 class="text-lg font-semibold text-white mb-4">Actions Admin</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Gérer les utilisateurs ({{ $totalUsers }})
                </a>
                <a href="{{ route('admin.moderation.index') }}" class="btn-warning">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Modération ({{ $pendingMod }} en attente)
                </a>
                <a href="{{ route('demandes.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Toutes les demandes
                </a>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
