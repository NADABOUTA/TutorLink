<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ Auth::user()->hasRole('apprenant') ? __('Mes Demandes de cours') : __('Demandes de cours disponibles') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ Auth::user()->hasRole('apprenant') 
                        ? __('Gérez vos demandes d\'accompagnement et consultez les offres reçues.') 
                        : __('Parcourez les besoins des apprenants et proposez votre accompagnement.') }}
                </p>
            </div>
            @if(Auth::user()->hasRole('apprenant') || Auth::user()->hasRole('admin'))
                <a href="{{ route('demandes.create') }}" 
                   id="btn-create-demande"
                   class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-sm hover:shadow transition-all duration-200 gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Publier une demande') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Filtres & Recherche -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <form method="GET" action="{{ route('demandes.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Recherche matière -->
                    <div>
                        <label for="filter-matiere" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                            {{ __('Matière') }}
                        </label>
                        <input type="text" 
                               name="matiere" 
                               id="filter-matiere" 
                               value="{{ request('matiere') }}" 
                               placeholder="Ex: Mathématiques, Anglais..." 
                               class="w-full text-sm rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Filtre niveau -->
                    <div>
                        <label for="filter-niveau" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                            {{ __('Niveau') }}
                        </label>
                        <select name="niveau" 
                                id="filter-niveau" 
                                class="w-full text-sm rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('Tous les niveaux') }}</option>
                            @foreach($niveauxDisponibles as $niveau)
                                <option value="{{ $niveau }}" {{ request('niveau') === $niveau ? 'selected' : '' }}>
                                    {{ $niveau }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre statut (pour apprenants) -->
                    @if(Auth::user()->hasRole('apprenant'))
                        <div>
                            <label for="filter-statut" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                                {{ __('Statut') }}
                            </label>
                            <select name="statut" 
                                    id="filter-statut" 
                                    class="w-full text-sm rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('Tous les statuts') }}</option>
                                <option value="en_attente_moderation" {{ request('statut') === 'en_attente_moderation' ? 'selected' : '' }}>En attente de modération</option>
                                <option value="ouverte" {{ request('statut') === 'ouverte' ? 'selected' : '' }}>Ouverte (acceptant des offres)</option>
                                <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="terminee" {{ request('statut') === 'terminee' ? 'selected' : '' }}>Terminée</option>
                                <option value="refusee" {{ request('statut') === 'refusee' ? 'selected' : '' }}>Refusée</option>
                            </select>
                        </div>
                    @endif

                    <!-- Boutons Filtrer / Réinitialiser -->
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                                class="flex-1 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 dark:bg-gray-100 dark:hover:bg-white dark:text-gray-900 text-white font-medium text-sm rounded-xl transition-all duration-150 text-center">
                            {{ __('Filtrer') }}
                        </button>
                        @if(request()->hasAny(['matiere', 'niveau', 'statut']))
                            <a href="{{ route('demandes.index') }}" 
                               class="px-3 py-2.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                {{ __('Effacer') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Liste des Demandes -->
            @if($demandes->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                    <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
                        🔍
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">
                        {{ __('Aucune demande trouvée') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">
                        {{ Auth::user()->hasRole('apprenant') 
                            ? __('Vous n\'avez pas encore créé de demande avec ces critères. Publiez-en une dès maintenant !') 
                            : __('Aucune demande disponible pour le moment avec ces critères. Revenez un peu plus tard !') }}
                    </p>
                    @if(Auth::user()->hasRole('apprenant'))
                        <a href="{{ route('demandes.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-sm transition-all">
                            {{ __('Créer une demande') }}
                        </a>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($demandes as $demande)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 transition-all duration-200 flex flex-col justify-between overflow-hidden group">
                            <div class="p-6">
                                <!-- En-tête de la carte : Matière & Statut -->
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div>
                                        <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                                            {{ $demande->niveau }}
                                        </span>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-2 group-hover:text-indigo-600 transition-colors">
                                            {{ $demande->matiere }}
                                        </h3>
                                    </div>

                                    @php
                                        $badgeColors = [
                                            'en_attente_moderation' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                            'ouverte' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                            'en_cours' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                            'terminee' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600',
                                            'refusee' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                        ];
                                        $labels = [
                                            'en_attente_moderation' => 'En modération',
                                            'ouverte' => 'Ouverte',
                                            'en_cours' => 'En cours',
                                            'terminee' => 'Terminée',
                                            'refusee' => 'Refusée',
                                        ];
                                    @endphp

                                    <span class="text-xs px-2.5 py-1 rounded-full font-medium border {{ $badgeColors[$demande->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $labels[$demande->statut] ?? $demande->statut }}
                                    </span>
                                </div>

                                <!-- Description courte -->
                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4 leading-relaxed">
                                    {{ $demande->description }}
                                </p>

                                <!-- Budget & Auteur -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700/60 text-xs text-gray-500">
                                    <div>
                                        <span class="block font-semibold text-sm text-gray-900 dark:text-gray-100">
                                            {{ number_format($demande->budget, 0) }} DH
                                        </span>
                                        <span>Budget proposé</span>
                                    </div>

                                    <div class="text-right">
                                        <span class="inline-flex items-center gap-1 font-semibold text-sm text-indigo-600 dark:text-indigo-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                            {{ $demande->offres_count }} {{ Str::plural('offre', $demande->offres_count) }}
                                        </span>
                                        <span class="block text-gray-400 text-[11px]">{{ $demande->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Pied de carte : Action -->
                            <div class="px-6 py-3.5 bg-gray-50 dark:bg-gray-850 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                                <a href="{{ route('demandes.show', $demande) }}" 
                                   class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 inline-flex items-center gap-1">
                                    {{ __('Voir les détails') }}
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>

                                @can('update', $demande)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('demandes.edit', $demande) }}" 
                                           class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            {{ __('Modifier') }}
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $demandes->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
