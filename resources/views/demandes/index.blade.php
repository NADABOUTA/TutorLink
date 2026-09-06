<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">
                    {{ Auth::user()->hasRole('apprenant') ? 'Mes demandes de cours' : 'Demandes disponibles' }}
                </h1>
                <p class="page-subtitle">
                    {{ Auth::user()->hasRole('apprenant')
                        ? 'Gérez vos demandes et consultez les offres reçues.'
                        : 'Parcourez les besoins des apprenants et proposez votre aide.' }}
                </p>
            </div>
            @if(Auth::user()->hasRole('apprenant'))
                <a href="{{ route('demandes.create') }}" id="btn-create-demande" class="btn-primary flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouvelle demande
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Filtres --}}
        <div class="card">
            <form method="GET" action="{{ route('demandes.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="filter-matiere" class="form-label">Matière</label>
                    <input type="text" name="matiere" id="filter-matiere" value="{{ request('matiere') }}"
                           class="form-input" placeholder="Ex: Mathématiques...">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="filter-niveau" class="form-label">Niveau</label>
                    <select name="niveau" id="filter-niveau" class="form-select">
                        <option value="">Tous les niveaux</option>
                        @foreach($niveauxDisponibles as $niveau)
                            <option value="{{ $niveau }}" {{ request('niveau') === $niveau ? 'selected' : '' }}>{{ $niveau }}</option>
                        @endforeach
                    </select>
                </div>
                @if(Auth::user()->hasRole('apprenant'))
                    <div class="form-group" style="margin-bottom:0;">
                        <label for="filter-statut" class="form-label">Statut</label>
                        <select name="statut" id="filter-statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="en_moderation" {{ request('statut') === 'en_moderation' ? 'selected' : '' }}>En modération</option>
                            <option value="ouverte" {{ request('statut') === 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                            <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminee" {{ request('statut') === 'terminee' ? 'selected' : '' }}>Terminée</option>
                            <option value="refusee" {{ request('statut') === 'refusee' ? 'selected' : '' }}>Refusée</option>
                        </select>
                    </div>
                @endif
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Filtrer
                    </button>
                    @if(request()->hasAny(['matiere', 'niveau', 'statut']))
                        <a href="{{ route('demandes.index') }}" class="btn-secondary px-3">✕</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Liste --}}
        @if($demandes->isEmpty())
            <div class="card">
                <div class="empty-state">
                    <div class="empty-state-icon">🔍</div>
                    <h3 class="empty-state-title">Aucune demande trouvée</h3>
                    <p class="empty-state-desc">
                        {{ Auth::user()->hasRole('apprenant')
                            ? 'Publiez votre première demande pour trouver un tuteur.'
                            : 'Aucune demande disponible pour le moment.' }}
                    </p>
                    @if(Auth::user()->hasRole('apprenant'))
                        <a href="{{ route('demandes.create') }}" class="btn-primary">Créer une demande</a>
                    @endif
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 stagger-children">
                @foreach($demandes as $demande)
                    @php
                        $statuts = [
                            'en_attente_moderation' => ['badge-pending', '⏳ En attente'],
                            'en_moderation'         => ['badge-pending', '⏳ En attente'],
                            'ouverte'               => ['badge-success', '✅ Ouverte'],
                            'en_cours'              => ['badge-info',    '🔵 En cours'],
                            'terminee'              => ['badge-purple',  '✓ Terminée'],
                            'refusee'               => ['badge-danger',  '✕ Refusée'],
                        ];
                        [$badgeCls, $badgeLabel] = $statuts[$demande->statut] ?? ['badge-info', $demande->statut];
                    @endphp
                    <div class="demande-card flex flex-col">
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <span class="badge-info text-xs px-2 py-0.5 rounded-lg" style="font-size:11px;">{{ $demande->niveau }}</span>
                                    <h3 class="text-lg font-bold text-white mt-2">{{ $demande->matiere }}</h3>
                                </div>
                                <span class="{{ $badgeCls }}" style="white-space:nowrap;">{{ $badgeLabel }}</span>
                            </div>

                            <p class="text-sm mb-4 line-clamp-3 leading-relaxed" style="color: rgb(148,163,184);">
                                {{ $demande->description }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                            <div>
                                <span class="text-base font-bold text-white">{{ number_format($demande->budget, 0) }} DH</span>
                                <span class="text-xs block" style="color: rgb(148,163,184);">Budget</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold" style="color: rgb(99,102,241);">{{ $demande->offres_count }} offre(s)</span>
                                <span class="text-xs block" style="color: rgb(148,163,184);">{{ $demande->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.04);">
                            <a href="{{ route('demandes.show', $demande) }}" class="text-sm font-semibold hover:underline" style="color: rgb(99,102,241); text-decoration:none;">
                                Voir les détails →
                            </a>
                            @can('update', $demande)
                                <a href="{{ route('demandes.edit', $demande) }}" class="text-xs" style="color: rgb(148,163,184); text-decoration:none;">Modifier</a>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $demandes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
