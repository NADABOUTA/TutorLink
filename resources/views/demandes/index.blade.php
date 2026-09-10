<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">
                    {{ Auth::user()->hasRole('apprenant') ? 'Mes demandes de cours' : 'Demandes d\'élèves disponibles' }}
                </h1>
                <p class="page-subtitle">
                    {{ Auth::user()->hasRole('apprenant')
                        ? 'Suivez l\'avancement de vos demandes et découvrez les propositions des tuteurs.'
                        : 'Explorez les besoins des apprenants partout au Maroc et proposez vos cours sur-mesure.' }}
                </p>
            </div>
            @if(Auth::user()->hasRole('apprenant'))
                <a href="{{ route('demandes.create') }}" id="btn-create-demande" class="btn-primary flex-shrink-0 no-underline shadow-md">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouvelle demande
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- FILTRES MODERNES --}}
        <div class="card p-5">
            <form method="GET" action="{{ route('demandes.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div class="form-group mb-0">
                    <label for="filter-matiere" class="form-label">Matière ou Mot-clé</label>
                    <input type="text" name="matiere" id="filter-matiere" value="{{ request('matiere') }}"
                           class="form-input" placeholder="Ex: Mathématiques, SVT, Anglais...">
                </div>
                <div class="form-group mb-0">
                    <label for="filter-niveau" class="form-label">Niveau académique</label>
                    <select name="niveau" id="filter-niveau" class="form-select">
                        <option value="">Tous les niveaux</option>
                        @foreach($niveauxDisponibles as $niveau)
                            <option value="{{ $niveau }}" {{ request('niveau') === $niveau ? 'selected' : '' }}>{{ $niveau }}</option>
                        @endforeach
                    </select>
                </div>
                @if(Auth::user()->hasRole('apprenant'))
                    <div class="form-group mb-0">
                        <label for="filter-statut" class="form-label">Statut de la demande</label>
                        <select name="statut" id="filter-statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="en_moderation" {{ request('statut') === 'en_moderation' ? 'selected' : '' }}>En modération</option>
                            <option value="ouverte" {{ request('statut') === 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                            <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminee" {{ request('statut') === 'terminee' ? 'selected' : '' }}>Terminée</option>
                            <option value="refusee" {{ request('statut') === 'refusee' ? 'selected' : '' }}>Refusée</option>
                        </select>
                    </div>
                @else
                    <div class="form-group mb-0">
                        <label class="form-label">Plateforme</label>
                        <div class="form-input bg-slate-50 text-slate-500 font-medium select-none flex items-center gap-2 cursor-default">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Tuteurs certifiés Maroc</span>
                        </div>
                    </div>
                @endif
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary flex-1 py-3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Filtrer
                    </button>
                    @if(request()->hasAny(['matiere', 'niveau', 'statut']))
                        <a href="{{ route('demandes.index') }}" class="btn-secondary px-3.5 py-3" title="Réinitialiser les filtres">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- LISTE DES DEMANDES --}}
        @if($demandes->isEmpty())
            <div class="card">
                <div class="empty-state py-16 text-center">
                    <div class="w-14 h-14 mx-auto mb-3.5 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Aucune demande trouvée</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 leading-relaxed">
                        {{ Auth::user()->hasRole('apprenant')
                            ? 'Vous n\'avez pas encore créé de demande de cours.'
                            : 'Aucune demande ouverte ne correspond à vos critères de recherche actuels.' }}
                    </p>
                    @if(Auth::user()->hasRole('apprenant'))
                        <a href="{{ route('demandes.create') }}" class="btn-primary mt-4 inline-flex items-center gap-2 no-underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Publier ma demande
                        </a>
                    @endif
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($demandes as $demande)
                    @php
                        $statuts = [
                            'en_attente_moderation' => ['badge-pending', 'En modération'],
                            'en_moderation'         => ['badge-pending', 'En modération'],
                            'ouverte'               => ['badge-success', 'Ouverte'],
                            'en_cours'              => ['badge-info',    'En cours'],
                            'terminee'              => ['badge-purple',  'Terminée'],
                            'refusee'               => ['badge-danger',  'Refusée'],
                        ];
                        [$badgeCls, $badgeLabel] = $statuts[$demande->statut] ?? ['badge-info', $demande->statut];
                    @endphp
                    <div class="demande-card flex flex-col justify-between">
                        <div>
                            {{-- Header carte --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <span class="badge-info text-[11px]">{{ $demande->niveau }}</span>
                                    <h3 class="text-lg font-bold text-slate-900 mt-2 tracking-tight">{{ $demande->matiere }}</h3>
                                </div>
                                <span class="{{ $badgeCls }} flex-shrink-0">{{ $badgeLabel }}</span>
                            </div>

                            {{-- Description --}}
                            <p class="text-xs text-slate-600 mb-5 line-clamp-3 leading-relaxed">
                                {{ $demande->description }}
                            </p>
                        </div>

                        <div>
                            {{-- Budget & Candidatures --}}
                            <div class="flex items-center justify-between py-3.5 border-t border-slate-100">
                                <div>
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Budget prévu</span>
                                    <span class="text-lg font-black text-blue-600">{{ number_format($demande->budget, 0) }} <span class="text-xs font-bold text-slate-500">DH</span></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Offres reçues</span>
                                    <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-full inline-block mt-0.5">
                                        {{ $demande->offres_count }} proposition(s)
                                    </span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                <a href="{{ route('demandes.show', $demande) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1 group no-underline">
                                    <span>Consulter la demande</span>
                                    <span class="group-hover:translate-x-0.5 transition-transform">→</span>
                                </a>
                                @can('update', $demande)
                                    <a href="{{ route('demandes.edit', $demande) }}" class="text-xs font-semibold text-slate-400 hover:text-slate-600 no-underline">
                                        Modifier
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $demandes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
