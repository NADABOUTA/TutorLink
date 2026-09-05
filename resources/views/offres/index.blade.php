<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ __('Mes propositions d\'offres') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('Suivez l\'état de vos propositions faites aux apprenants.') }}
                </p>
            </div>

            <a href="{{ route('demandes.index') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                {{ __('Voir les demandes ouvertes') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash messages -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Onglets de filtre -->
            <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700 pb-3">
                <a href="{{ route('offres.index') }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ !request('statut') ? 'bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900 shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    Toutes ({{ $counts['all'] }})
                </a>

                <a href="{{ route('offres.index', ['statut' => 'en_attente']) }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ request('statut') === 'en_attente' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    ⏳ En attente ({{ $counts['en_attente'] }})
                </a>

                <a href="{{ route('offres.index', ['statut' => 'acceptee']) }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ request('statut') === 'acceptee' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    🎉 Acceptées ({{ $counts['acceptee'] }})
                </a>

                <a href="{{ route('offres.index', ['statut' => 'refusee']) }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ request('statut') === 'refusee' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    ❌ Non retenues ({{ $counts['refusee'] }})
                </a>
            </div>

            <!-- Liste des offres -->
            @if($offres->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                    <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
                        💼
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">
                        Aucune offre trouvée
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">
                        Vous n'avez pas encore envoyé d'offre correspondant à ces critères.
                    </p>
                    <a href="{{ route('demandes.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-sm transition-all">
                        Explorer les demandes
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($offres as $offre)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 flex flex-col justify-between overflow-hidden transition-all duration-200">
                            <div class="p-6">
                                <!-- En-tête : Demande & Statut -->
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div>
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                                            {{ $offre->demande->niveau }}
                                        </span>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">
                                            {{ $offre->demande->matiere }}
                                        </h3>
                                        <p class="text-xs text-gray-500">Pour : <strong>{{ $offre->demande->apprenant->name }}</strong></p>
                                    </div>

                                    @php
                                        $badgeColors = [
                                            'en_attente' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border-amber-200',
                                            'acceptee' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border-emerald-200',
                                            'refusee' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 border-rose-200',
                                        ];
                                        $labels = [
                                            'en_attente' => 'En attente',
                                            'acceptee' => 'Acceptée 🎉',
                                            'refusee' => 'Non retenue',
                                        ];
                                    @endphp

                                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold border {{ $badgeColors[$offre->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $labels[$offre->statut] ?? $offre->statut }}
                                    </span>
                                </div>

                                <!-- Mon message -->
                                <div class="my-4">
                                    <span class="text-xs font-semibold text-gray-400 block mb-1">Votre message :</span>
                                    <p class="text-xs text-gray-700 dark:text-gray-300 line-clamp-3 leading-relaxed bg-gray-50 dark:bg-gray-900/60 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                                        {{ $offre->message }}
                                    </p>
                                </div>

                                <!-- Tarif proposé -->
                                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700/60 text-xs">
                                    <span class="text-gray-500">Tarif proposé</span>
                                    <span class="text-base font-extrabold text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($offre->tarif_propose, 0) }} DH
                                    </span>
                                </div>

                                <!-- Coordonnées débloquées si acceptée -->
                                @if($offre->statut === 'acceptee' && $offre->coordonnees_visibles)
                                    <div class="mt-4 p-3.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs space-y-1 text-emerald-900 dark:text-emerald-200">
                                        <p class="font-bold flex items-center gap-1.5 text-emerald-800 dark:text-emerald-300">
                                            <span>📞</span> Contact de l'apprenant :
                                        </p>
                                        <p>Email : <a href="mailto:{{ $offre->demande->apprenant->email }}" class="underline font-semibold">{{ $offre->demande->apprenant->email }}</a></p>
                                        @if($offre->demande->apprenant->telephone)
                                            <p>Téléphone : <a href="tel:{{ $offre->demande->apprenant->telephone }}" class="underline font-bold">{{ $offre->demande->apprenant->telephone }}</a></p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-850 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs">
                                <span class="text-gray-400">{{ $offre->created_at->diffForHumans() }}</span>
                                <a href="{{ route('demandes.show', $offre->demande_id) }}" 
                                   class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Voir la demande &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $offres->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
