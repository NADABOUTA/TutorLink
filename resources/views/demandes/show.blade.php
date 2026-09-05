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
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Publiée par <strong class="text-gray-700 dark:text-gray-300">{{ $demande->apprenant->name }}</strong> &bull; {{ $demande->created_at->translatedFormat('d F Y à H:i') }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('demandes.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                    &larr; {{ __('Retour') }}
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

            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Statut et alertes -->
            @if($demande->statut === 'en_attente_moderation')
                <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 flex items-start gap-3">
                    <span class="text-xl">⏳</span>
                    <div>
                        <h2 class="text-sm font-bold text-amber-900 dark:text-amber-200">Demande en attente de modération</h2>
                        <p class="text-xs text-amber-800 dark:text-amber-300 mt-1">
                            Votre demande a été enregistrée. Elle est actuellement examinée par notre équipe de modération. Dès validation, les tuteurs pourront vous soumettre leurs offres.
                        </p>
                    </div>
                </div>
            @elseif($demande->statut === 'refusee')
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 flex items-start gap-3">
                    <span class="text-xl">❌</span>
                    <div>
                        <h2 class="text-sm font-bold text-rose-900 dark:text-rose-200">Demande refusée par la modération</h2>
                        <p class="text-xs text-rose-800 dark:text-rose-300 mt-1">
                            <strong>Motif :</strong> {{ $demande->motif_refus ?? 'Non conforme aux règles de la plateforme.' }}
                        </p>
                        <div class="mt-2">
                            <a href="{{ route('demandes.edit', $demande) }}" class="text-xs font-semibold underline text-rose-800 dark:text-rose-300 hover:text-rose-900">
                                Modifier la demande pour la soumettre à nouveau
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Détails principaux -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Matière</span>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $demande->matiere }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Niveau</span>
                        <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $demande->niveau }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Budget proposé</span>
                        <span class="text-xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ number_format($demande->budget, 0) }} DH</span>
                    </div>
                </div>

                <div class="mt-6">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Description du besoin</h2>
                    <div class="prose dark:prose-invert max-w-none text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                        {{ $demande->description }}
                    </div>
                </div>
            </div>

            <!-- Section Offres reçues / faites -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                            Offres des tuteurs ({{ $demande->offres->count() }})
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Propositions d'accompagnement soumises pour cette demande.
                        </p>
                    </div>

                    @if(Auth::user()->hasRole('tuteur') && $demande->statut === 'ouverte')
                        @php
                            $alreadyOffered = $demande->offres->where('tuteur_id', Auth::id())->first();
                        @endphp

                        @if(!$alreadyOffered)
                            <a href="#proposer-offre" 
                               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-all">
                                Proposer mon offre
                            </a>
                        @endif
                    @endif
                </div>

                @if($demande->offres->isEmpty())
                    <div class="text-center py-8 text-gray-500 text-sm">
                        <div class="text-2xl mb-2">💬</div>
                        <p>Aucune offre reçue pour le moment.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($demande->offres as $offre)
                            <div class="p-5 rounded-xl border {{ $offre->statut === 'acceptee' ? 'border-emerald-300 bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-800' : 'border-gray-100 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/40' }} flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300 font-bold flex items-center justify-center text-sm">
                                            {{ strtoupper(substr($offre->tuteur->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                {{ $offre->tuteur->name }}
                                            </h3>
                                            @if($offre->tuteur->matiere)
                                                <p class="text-xs text-gray-500">Spécialité : {{ $offre->tuteur->matiere }}</p>
                                            @endif
                                        </div>
                                        
                                        @if($offre->statut === 'acceptee')
                                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">
                                                Offre Acceptée
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-sm text-gray-700 dark:text-gray-300 pl-13 leading-relaxed">
                                        {{ $offre->message }}
                                    </p>

                                    <!-- Coordonnées révélées si offre acceptée -->
                                    @if($offre->coordonnees_visibles)
                                        <div class="mt-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-900 dark:text-emerald-200 space-y-1">
                                            <p class="font-bold flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400">
                                                <span>📞</span> Coordonnées de contact partagées :
                                            </p>
                                            <p>Email : <a href="mailto:{{ $offre->tuteur->email }}" class="underline">{{ $offre->tuteur->email }}</a></p>
                                            @if($offre->tuteur->telephone)
                                                <p>Téléphone : <a href="tel:{{ $offre->tuteur->telephone }}" class="underline font-semibold">{{ $offre->tuteur->telephone }}</a></p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="text-right flex-shrink-0">
                                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100 block">
                                        {{ number_format($offre->tarif_propose, 0) }} DH
                                    </span>
                                    <span class="text-xs text-gray-400">Tarif proposé</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
