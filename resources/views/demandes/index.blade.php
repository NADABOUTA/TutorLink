<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="page-title text-2xl font-black text-white font-display">
                    {{ Auth::user()->hasRole('apprenant') ? 'Mes demandes de cours' : 'Demandes d\'élèves' }}
                </h1>
                <p class="page-subtitle text-xs text-slate-400 mt-0.5">
                    {{ Auth::user()->hasRole('apprenant')
                        ? 'Suivez vos demandes et les propositions reçues de mentors qualifiés.'
                        : 'Explorez les besoins des apprenants et proposez vos compétences.' }}
                </p>
            </div>
            @if(Auth::user()->hasRole('apprenant'))
                <a href="{{ route('demandes.create') }}" class="btn-primary flex-shrink-0 no-underline text-xs py-2.5 px-5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Nouvelle demande
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Filters --}}
        <div class="card p-6">
            <form method="GET" action="{{ route('demandes.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div class="form-group mb-0">
                    <label for="filter-matiere" class="form-label">Matière</label>
                    <input type="text" name="matiere" id="filter-matiere" value="{{ request('matiere') }}"
                           class="form-input" placeholder="Ex: Mathématiques, SVT...">
                </div>
                <div class="form-group mb-0">
                    <label for="filter-niveau" class="form-label">Niveau</label>
                    <select name="niveau" id="filter-niveau" class="form-select">
                        <option value="">Tous les niveaux</option>
                        @foreach($niveauxDisponibles as $niveau)
                            <option value="{{ $niveau }}" {{ request('niveau') === $niveau ? 'selected' : '' }}>{{ $niveau }}</option>
                        @endforeach
                    </select>
                </div>
                @if(Auth::user()->hasRole('apprenant'))
                    <div class="form-group mb-0">
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
                @else
                    <div class="form-group mb-0">
                        <label class="form-label">Plateforme</label>
                        <div class="form-input bg-[#0B101B] text-slate-400 font-medium select-none flex items-center gap-2 cursor-default border-slate-800">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Tuteurs certifiés
                        </div>
                    </div>
                @endif
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary flex-1 text-xs py-2.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Filtrer
                    </button>
                    @if(request()->hasAny(['matiere', 'niveau', 'statut']))
                        <a href="{{ route('demandes.index') }}" class="btn-secondary px-3.5 py-2.5 text-xs" title="Réinitialiser">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if($demandes->isEmpty())
            <div class="card p-12 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-slate-800/60 flex items-center justify-center text-slate-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-base font-bold text-white">Aucune demande trouvée</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                    {{ Auth::user()->hasRole('apprenant')
                        ? 'Vous n\'avez pas encore créé de demande pour le moment.'
                        : 'Aucune demande ne correspond à vos critères actuels.' }}
                </p>
                @if(Auth::user()->hasRole('apprenant'))
                    <a href="{{ route('demandes.create') }}" class="btn-primary mt-4 inline-flex items-center gap-2 no-underline text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Publier une demande
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($demandes as $demande)
                    @php
                        $statuts = [
                            'en_attente_moderation' => ['bg-amber-500/10 text-amber-400 border border-amber-500/30', 'En modération'],
                            'en_moderation'         => ['bg-amber-500/10 text-amber-400 border border-amber-500/30', 'En modération'],
                            'ouverte'               => ['bg-emerald-500/10 text-emerald-400 border border-emerald-500/30', 'Ouverte'],
                            'en_cours'              => ['bg-sky-500/10 text-sky-400 border border-sky-500/30', 'En cours'],
                            'terminee'              => ['bg-purple-500/10 text-purple-400 border border-purple-500/30', 'Terminée'],
                            'refusee'               => ['bg-rose-500/10 text-rose-400 border border-rose-500/30', 'Refusée'],
                        ];
                        [$badgeCls, $badgeLabel] = $statuts[$demande->statut] ?? ['bg-slate-800 text-slate-300', $demande->statut];
                    @endphp
                    <div class="card p-6 rounded-2xl flex flex-col justify-between hover:border-amber-500/40 transition-all duration-200 group shadow-lg">
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-sky-500/10 text-sky-400 border border-sky-500/20">{{ $demande->niveau }}</span>
                                    <h3 class="text-base font-bold text-white mt-2 group-hover:text-amber-400 transition">{{ $demande->matiere }}</h3>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeCls }} flex-shrink-0">{{ $badgeLabel }}</span>
                            </div>
                            <p class="text-xs text-slate-400 mb-4 line-clamp-3 leading-relaxed">{{ $demande->description }}</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-between py-3 border-t border-slate-800">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Budget</span>
                                    <span class="text-xl font-black text-amber-400">{{ number_format($demande->budget, 0) }} <span class="text-xs font-normal text-slate-400">DH</span></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Offres</span>
                                    <span class="text-xs font-bold text-slate-300 bg-[#0B101B] border border-slate-800 px-2.5 py-1 rounded-full inline-block mt-0.5">
                                        {{ $demande->offres_count }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-slate-800">
                                <a href="{{ route('demandes.show', $demande) }}" class="text-xs font-bold text-amber-400 hover:text-amber-300 inline-flex items-center gap-1 no-underline transition">
                                    Consulter <span class="text-[10px]">&rarr;</span>
                                </a>
                                @can('update', $demande)
                                    <a href="{{ route('demandes.edit', $demande) }}" class="text-xs font-semibold text-slate-400 hover:text-white no-underline transition">Modifier</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $demandes->links() }}</div>
        @endif
    </div>
</x-app-layout>
