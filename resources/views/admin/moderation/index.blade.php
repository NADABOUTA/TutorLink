<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ __('Espace de Modération') }}
                    </h1>
                    @if($counts['en_attente_moderation'] > 0)
                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-500 text-white animate-pulse">
                            {{ $counts['en_attente_moderation'] }} en attente
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('Validez ou refusez les demandes de cours soumises par les apprenants.') }}
                </p>
            </div>
            
            <div class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700">
                🛡️ Accès Administrateur
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Message Flash -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Onglets de filtre -->
            <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700 pb-3">
                <a href="{{ route('admin.moderation.index', ['statut' => 'en_attente_moderation']) }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-150 flex items-center gap-2 {{ $status === 'en_attente_moderation' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    <span>⏳ À modérer</span>
                    <span class="px-2 py-0.5 text-xs rounded-full {{ $status === 'en_attente_moderation' ? 'bg-amber-700 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                        {{ $counts['en_attente_moderation'] }}
                    </span>
                </a>

                <a href="{{ route('admin.moderation.index', ['statut' => 'ouverte']) }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-150 flex items-center gap-2 {{ $status === 'ouverte' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    <span>✅ Ouvertes</span>
                    <span class="px-2 py-0.5 text-xs rounded-full {{ $status === 'ouverte' ? 'bg-emerald-700 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                        {{ $counts['ouverte'] }}
                    </span>
                </a>

                <a href="{{ route('admin.moderation.index', ['statut' => 'refusee']) }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-150 flex items-center gap-2 {{ $status === 'refusee' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    <span>❌ Refusées</span>
                    <span class="px-2 py-0.5 text-xs rounded-full {{ $status === 'refusee' ? 'bg-rose-700 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                        {{ $counts['refusee'] }}
                    </span>
                </a>

                <a href="{{ route('admin.moderation.index', ['statut' => 'all']) }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-150 flex items-center gap-2 {{ $status === 'all' ? 'bg-gray-900 text-white shadow-sm dark:bg-gray-100 dark:text-gray-900' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    <span>📋 Toutes ({{ $counts['all'] }})</span>
                </a>
            </div>

            <!-- Liste des demandes -->
            @if($demandes->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                    <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
                        ✨
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">
                        Aucune demande dans cette catégorie
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Toutes les demandes soumises ont été traitées.
                    </p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($demandes as $demande)
                        <div x-data="{ openRefuse: false }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 sm:p-7">
                                <!-- En-tête : Matière, Niveau, Statut & Date -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            #{{ $demande->id }} — {{ $demande->matiere }}
                                        </h2>
                                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                                            {{ $demande->niveau }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        @php
                                            $badgeColors = [
                                                'en_attente_moderation' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                                                'ouverte' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                                                'en_cours' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                                                'terminee' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                'refusee' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
                                            ];
                                            $labels = [
                                                'en_attente_moderation' => 'En attente',
                                                'ouverte' => 'Ouverte',
                                                'en_cours' => 'En cours',
                                                'terminee' => 'Terminée',
                                                'refusee' => 'Refusée',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $badgeColors[$demande->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $labels[$demande->statut] ?? $demande->statut }}
                                        </span>

                                        <span class="text-xs text-gray-400">
                                            {{ $demande->created_at->translatedFormat('d M Y à H:i') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Infos Apprenant & Budget -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 py-4 bg-gray-50/60 dark:bg-gray-900/30 px-4 rounded-xl my-4 text-xs">
                                    <div>
                                        <span class="text-gray-400 block font-medium">Apprenant :</span>
                                        <strong class="text-gray-800 dark:text-gray-200 text-sm">{{ $demande->apprenant->name }}</strong>
                                        <span class="text-gray-500 block">{{ $demande->apprenant->email }}</span>
                                        @if($demande->apprenant->telephone)
                                            <span class="text-gray-500 block">📞 {{ $demande->apprenant->telephone }}</span>
                                        @endif
                                    </div>

                                    <div>
                                        <span class="text-gray-400 block font-medium">Budget proposé :</span>
                                        <strong class="text-indigo-600 dark:text-indigo-400 text-base font-bold">
                                            {{ number_format($demande->budget, 0) }} DH
                                        </strong>
                                    </div>

                                    <div>
                                        <span class="text-gray-400 block font-medium">Offres reçues :</span>
                                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $demande->offres_count }} offre(s)
                                        </span>
                                    </div>
                                </div>

                                <!-- Description de la demande -->
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                                        Description du besoin soumis :
                                    </h3>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-100 dark:border-gray-800 leading-relaxed whitespace-pre-line">
                                        {{ $demande->description }}
                                    </p>
                                </div>

                                <!-- Motif si refusée -->
                                @if($demande->statut === 'refusee' && $demande->motif_refus)
                                    <div class="mt-4 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-xs text-rose-800 dark:text-rose-300">
                                        <strong>Motif du refus enregistré :</strong> {{ $demande->motif_refus }}
                                    </div>
                                @endif

                                <!-- Actions Modérateur -->
                                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3">
                                    <a href="{{ route('demandes.show', $demande) }}" 
                                       target="_blank"
                                       class="text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 inline-flex items-center gap-1">
                                        <span>Consulter la vue publique</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>

                                    <div class="flex items-center gap-3">
                                        <!-- Formulaire Approuver -->
                                        @if($demande->statut !== 'ouverte')
                                            <form method="POST" action="{{ route('admin.moderation.approuver', $demande) }}" onsubmit="return confirm('Voulez-vous approuver cette demande et la rendre visible aux tuteurs ?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-150 flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    {{ __('Approuver') }}
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Bouton Refuser pour basculer le formulaire -->
                                        @if($demande->statut !== 'refusee')
                                            <button type="button" 
                                                    @click="openRefuse = !openRefuse"
                                                    class="px-4 py-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 text-xs font-bold rounded-xl transition-all duration-150 flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                {{ __('Refuser avec motif') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Formulaire de refus dépliable -->
                                <div x-show="openRefuse" 
                                     x-cloak 
                                     x-transition 
                                     class="mt-4 p-4 rounded-xl bg-rose-50/60 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800">
                                    <form method="POST" action="{{ route('admin.moderation.refuser', $demande) }}">
                                        @csrf
                                        @method('PATCH')

                                        <label for="motif_refus_{{ $demande->id }}" class="block text-xs font-bold text-rose-900 dark:text-rose-300 mb-1">
                                            Précisez la raison du refus (sera transmise à l'apprenant) :
                                        </label>

                                        <textarea id="motif_refus_{{ $demande->id }}" 
                                                  name="motif_refus" 
                                                  rows="3" 
                                                  required 
                                                  placeholder="Ex: La description manque de précisions sur le programme scolaire, ou le budget est manifestement irréaliste..."
                                                  class="w-full text-sm rounded-xl border-rose-300 dark:border-rose-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-rose-500 focus:border-rose-500"></textarea>

                                        <div class="flex items-center justify-end gap-2 mt-3">
                                            <button type="button" 
                                                    @click="openRefuse = false" 
                                                    class="px-3 py-1.5 text-xs text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                                                Annuler
                                            </button>
                                            <button type="submit" 
                                                    class="px-4 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition-all">
                                                Confirmer le refus
                                            </button>
                                        </div>
                                    </form>
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
    </div>
</x-app-layout>
