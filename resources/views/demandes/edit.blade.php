<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ __('Modifier la demande') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('Mettez à jour les informations de votre demande.') }}
                </p>
            </div>
            <a href="{{ route('demandes.show', $demande) }}" 
               class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 font-medium">
                &larr; {{ __('Retour aux détails') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 sm:p-8">

                    @if($demande->statut === 'refusee')
                        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/80 flex items-start gap-3">
                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <h3 class="text-sm font-bold text-rose-800 dark:text-rose-300">Demande précédemment refusée</h3>
                                <p class="text-xs text-rose-700 dark:text-rose-400 mt-1">
                                    <strong>Motif du refus :</strong> {{ $demande->motif_refus ?? 'Non spécifié.' }}
                                </p>
                                <p class="text-xs text-rose-600 dark:text-rose-400 mt-2">
                                    En enregistrant vos modifications, votre demande sera automatiquement renvoyée en modération.
                                </p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('demandes.update', $demande) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Matière -->
                        <div>
                            <x-input-label for="matiere" :value="__('Matière recherchée')" />
                            <x-text-input id="matiere" 
                                          type="text" 
                                          name="matiere" 
                                          :value="old('matiere', $demande->matiere)" 
                                          placeholder="Ex: Mathématiques, Physique-Chimie, Français..." 
                                          class="mt-1 block w-full" 
                                          required />
                            <x-input-error :messages="$errors->get('matiere')" class="mt-2" />
                        </div>

                        <!-- Niveau & Budget -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Niveau -->
                            <div>
                                <x-input-label for="niveau" :value="__('Niveau scolaire')" />
                                <select id="niveau" 
                                        name="niveau" 
                                        required 
                                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                                    @foreach($niveaux as $niveau)
                                        <option value="{{ $niveau }}" {{ old('niveau', $demande->niveau) === $niveau ? 'selected' : '' }}>
                                            {{ $niveau }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('niveau')" class="mt-2" />
                            </div>

                            <!-- Budget -->
                            <div>
                                <x-input-label for="budget" :value="__('Budget prévu (DH)')" />
                                <div class="relative mt-1">
                                    <x-text-input id="budget" 
                                                  type="number" 
                                                  step="0.01" 
                                                  min="1" 
                                                  name="budget" 
                                                  :value="old('budget', $demande->budget)" 
                                                  placeholder="Ex: 150" 
                                                  class="block w-full pr-12" 
                                                  required />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-500 text-xs font-semibold">
                                        DH
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description détaillée du besoin')" />
                            <textarea id="description" 
                                      name="description" 
                                      rows="5" 
                                      class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                      required>{{ old('description', $demande->description) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Minimum 20 caractères.</p>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('demandes.show', $demande) }}" 
                               class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button class="px-6 py-2.5 rounded-xl text-sm">
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
