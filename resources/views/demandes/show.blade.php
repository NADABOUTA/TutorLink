<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $demande->matiere }}
                    </h1>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                        {{ $demande->niveau }}
                    </span>
                    @php
                        $badgeColors = [
                            'en_attente_moderation' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                            'ouverte' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                            'en_cours' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                            'terminee' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                            'refusee' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
                        ];
                        $labels = [
                            'en_attente_moderation' => 'En modération',
                            'ouverte' => 'Ouverte',
                            'en_cours' => 'En cours',
                            'terminee' => 'Terminée',
                            'refusee' => 'Refusée',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $badgeColors[$demande->statut] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $labels[$demande->statut] ?? $demande->statut }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Publiée par <strong class="text-gray-700 dark:text-gray-300">{{ $demande->apprenant->name }}</strong> &bull; {{ $demande->created_at->translatedFormat('d F Y à H:i') }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('demandes.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                    &larr; {{ __('Retour aux demandes') }}
                </a>

                @can('update', $demande)
                    <a href="{{ route('demandes.edit', $demande) }}" 
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-xl transition-all">
                        {{ __('Modifier') }}
                    </a>
                @endcan

                @can('delete', $demande)
                    <form method="POST" action="{{ route('demandes.destroy', $demande) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 text-sm font-medium rounded-xl transition-all">
                            {{ __('Supprimer') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
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

            <!-- Alertes de statut -->
            @if($demande->statut === 'en_attente_moderation')
                <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 flex items-start gap-3">
                    <span class="text-xl">⏳</span>
                    <div>
                        <h2 class="text-sm font-bold text-amber-900 dark:text-amber-200">Demande en cours d'examen</h2>
                        <p class="text-xs text-amber-800 dark:text-amber-300 mt-1">
                            Votre demande a été transmise aux modérateurs. Dès qu'elle sera validée, elle sera accessible aux tuteurs pour recevoir leurs propositions.
                        </p>
                    </div>
                </div>
            @elseif($demande->statut === 'refusee')
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 flex items-start gap-3">
                    <span class="text-xl">❌</span>
                    <div>
                        <h2 class="text-sm font-bold text-rose-900 dark:text-rose-200">Demande refusée par la modération</h2>
                        <p class="text-xs text-rose-800 dark:text-rose-300 mt-1">
                            <strong>Motif :</strong> {{ $demande->motif_refus ?? 'Non conforme aux critères de publication.' }}
                        </p>
                        @can('update', $demande)
                            <div class="mt-2">
                                <a href="{{ route('demandes.edit', $demande) }}" class="text-xs font-semibold underline text-rose-800 dark:text-rose-300 hover:text-rose-900">
                                    Modifier et soumettre à nouveau
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            @elseif($demande->statut === 'en_cours')
                <div class="p-4 rounded-2xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 flex items-start gap-3">
                    <span class="text-xl">🤝</span>
                    <div>
                        <h2 class="text-sm font-bold text-blue-900 dark:text-blue-200">Accompagnement pédagogique en cours</h2>
                        <p class="text-xs text-blue-800 dark:text-blue-300 mt-1">
                            Une offre a été acceptée. Les coordonnées directes (téléphone et email) sont partagées ci-dessous pour convenir des séances.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Détails pédagogiques -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Matière</span>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $demande->matiere }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Niveau scolaire</span>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $demande->niveau }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Budget prévu</span>
                        <span class="text-xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ number_format($demande->budget, 0) }} DH</span>
                    </div>
                </div>

                <div class="mt-6">
                    <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Description du besoin</h2>
                    <div class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-100 dark:border-gray-800">
                        {{ $demande->description }}
                    </div>
                </div>
            </div>

            <!-- Formulaire pour le tuteur : Proposer une offre -->
            @if(Auth::user()->hasRole('tuteur') && $demande->statut === 'ouverte' && $demande->apprenant_id !== Auth::id())
                @php
                    $monOffre = $demande->offres->where('tuteur_id', Auth::id())->first();
                @endphp

                @if(!$monOffre)
                    <div id="proposer-offre" class="bg-gradient-to-br from-indigo-50/70 to-purple-50/70 dark:from-gray-800 dark:to-gray-800 rounded-2xl shadow-sm border border-indigo-100 dark:border-gray-700 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-2xl">✍️</span>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                    Proposer votre offre à cet apprenant
                                </h2>
                                <p class="text-xs text-gray-500">
                                    Présentez vos disponibilités, votre approche pédagogique et votre tarif proposé.
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('demandes.offres.store', $demande) }}" class="space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="tarif_propose" :value="__('Votre tarif proposé (DH)')" />
                                <div class="relative mt-1 max-w-xs">
                                    <x-text-input id="tarif_propose" 
                                                  type="number" 
                                                  step="0.01" 
                                                  min="1" 
                                                  name="tarif_propose" 
                                                  :value="old('tarif_propose', $demande->budget)" 
                                                  class="block w-full pr-12" 
                                                  required />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-500 text-xs font-semibold">
                                        DH
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('tarif_propose')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="message" :value="__('Message de présentation & modalités')" />
                                <textarea id="message" 
                                          name="message" 
                                          rows="4" 
                                          placeholder="Bonjour, je suis professeur expérimenté en {{ $demande->matiere }}... Je vous propose un programme adapté..."
                                          class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                          required>{{ old('message') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Minimum 15 caractères.</p>
                                <x-input-error :messages="$errors->get('message')" class="mt-2" />
                            </div>

                            <div class="flex justify-end pt-2">
                                <x-primary-button class="px-6 py-2.5 rounded-xl text-sm font-bold">
                                    {{ __('Envoyer mon offre') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-200 dark:border-indigo-800 flex items-center justify-between">
                        <div class="flex items-center gap-3 text-xs text-indigo-900 dark:text-indigo-200">
                            <span class="text-lg">✔️</span>
                            <span>Vous avez déjà soumis une offre de <strong>{{ number_format($monOffre->tarif_propose, 0) }} DH</strong> pour cette demande. Statut : <strong>{{ $monOffre->statut }}</strong>.</span>
                        </div>
                        <a href="{{ route('offres.index') }}" class="text-xs font-semibold underline text-indigo-700 dark:text-indigo-300">
                            Voir mes offres
                        </a>
                    </div>
                @endif
            @endif

            <!-- Section Offres reçues -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                            Propositions reçues ({{ $demande->offres->count() }})
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ Auth::id() === $demande->apprenant_id ? 'Consultez les propositions des tuteurs et choisissez celle qui vous convient.' : 'Liste des offres soumises pour cette demande.' }}
                        </p>
                    </div>
                </div>

                @if($demande->offres->isEmpty())
                    <div class="text-center py-10 text-gray-500">
                        <div class="text-3xl mb-2">💬</div>
                        <p class="text-sm">Aucune proposition pour l'instant.</p>
                        @if($demande->statut === 'ouverte' && Auth::user()->hasRole('tuteur'))
                            <p class="text-xs text-indigo-600 mt-1">Soyez le premier tuteur à postuler ci-dessus !</p>
                        @endif
                    </div>
                @else
                    <div class="space-y-5">
                        @foreach($demande->offres as $offre)
                            <div class="p-6 rounded-2xl border transition-all duration-200 {{ $offre->statut === 'acceptee' ? 'border-emerald-300 bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-800 shadow-sm' : ($offre->statut === 'refusee' ? 'border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/20 opacity-70' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-850') }}">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                    <div class="space-y-3 flex-1">
                                        <!-- Info Tuteur -->
                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-full bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300 font-bold flex items-center justify-center text-sm">
                                                {{ strtoupper(substr($offre->tuteur->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                        {{ $offre->tuteur->name }}
                                                    </h3>
                                                    @if($offre->tuteur->note_moyenne)
                                                        <span class="inline-flex items-center text-xs font-semibold text-amber-500">
                                                            ★ {{ $offre->tuteur->note_moyenne }} / 5
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($offre->tuteur->matiere)
                                                    <p class="text-xs text-gray-500">Spécialité : {{ $offre->tuteur->matiere }}</p>
                                                @endif
                                            </div>

                                            @if($offre->statut === 'acceptee')
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300 ml-auto sm:ml-2">
                                                    🎉 Offre Acceptée
                                                </span>
                                            @elseif($offre->statut === 'refusee')
                                                <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 ml-auto sm:ml-2">
                                                    Non retenue
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Message du tuteur -->
                                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed bg-gray-50/70 dark:bg-gray-900/40 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                                            {{ $offre->message }}
                                        </p>

                                        <!-- Coordonnées partagées si acceptée -->
                                        @if($offre->coordonnees_visibles)
                                            <div class="mt-4 p-4 bg-white dark:bg-gray-900 rounded-xl border border-emerald-300 dark:border-emerald-800 text-xs text-emerald-950 dark:text-emerald-200 space-y-2">
                                                <div class="flex items-center gap-2 font-bold text-emerald-800 dark:text-emerald-400">
                                                    <span class="text-base">📞</span>
                                                    <span>Coordonnées réciproques débloquées :</span>
                                                </div>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                                    <div class="p-2.5 bg-emerald-50/60 dark:bg-emerald-950/40 rounded-lg">
                                                        <span class="block text-gray-500 text-[11px]">Tuteur ({{ $offre->tuteur->name }})</span>
                                                        <p class="font-medium mt-0.5">Email : <a href="mailto:{{ $offre->tuteur->email }}" class="underline">{{ $offre->tuteur->email }}</a></p>
                                                        @if($offre->tuteur->telephone)
                                                            <p class="font-bold mt-0.5">Tél : <a href="tel:{{ $offre->tuteur->telephone }}" class="underline">{{ $offre->tuteur->telephone }}</a></p>
                                                        @endif
                                                    </div>

                                                    <div class="p-2.5 bg-emerald-50/60 dark:bg-emerald-950/40 rounded-lg">
                                                        <span class="block text-gray-500 text-[11px]">Apprenant ({{ $demande->apprenant->name }})</span>
                                                        <p class="font-medium mt-0.5">Email : <a href="mailto:{{ $demande->apprenant->email }}" class="underline">{{ $demande->apprenant->email }}</a></p>
                                                        @if($demande->apprenant->telephone)
                                                            <p class="font-bold mt-0.5">Tél : <a href="tel:{{ $demande->apprenant->telephone }}" class="underline">{{ $demande->apprenant->telephone }}</a></p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Tarif & Action Accepter -->
                                    <div class="sm:text-right flex flex-col justify-between sm:items-end gap-3 flex-shrink-0">
                                        <div>
                                            <span class="text-xs text-gray-400 block">Tarif proposé</span>
                                            <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400">
                                                {{ number_format($offre->tarif_propose, 0) }} DH
                                            </span>
                                        </div>

                                        <!-- Bouton Accepter (Seulement pour l'apprenant propriétaire et si la demande est encore ouverte) -->
                                        @if(Auth::id() === $demande->apprenant_id && $demande->statut === 'ouverte' && $offre->statut === 'en_attente')
                                            <form method="POST" action="{{ route('offres.accepter', $offre) }}" onsubmit="return confirm('En acceptant cette offre, vous confirmez votre choix avec ce tuteur. Les autres offres seront refusées. Continuer ?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm hover:shadow transition-all duration-150 flex items-center justify-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    {{ __('Accepter cette offre') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
