<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ __('Publier une nouvelle demande de cours') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('Précisez vos besoins pour recevoir des propositions de tuteurs qualifiés.') }}
                </p>
            </div>
            <a href="{{ route('demandes.index') }}" 
               class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 font-medium">
                &larr; {{ __('Retour aux demandes') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 sm:p-8">

                    <!-- Note d'information sur la modération -->
                    <div class="mb-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/80 flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                            <strong>Note de sécurité :</strong> Toute demande créée passe d'abord par une étape de modération pour vérifier sa conformité. Dès sa validation par un administrateur, elle deviendra visible auprès des tuteurs de la plateforme.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('demandes.store') }}" class="space-y-6">
                        @csrf

                        <!-- Matière -->
                        <div>
                            <x-input-label for="matiere" :value="__('Matière recherchée')" />
                            <x-text-input id="matiere" 
                                          type="text" 
                                          name="matiere" 
                                          :value="old('matiere')" 
                                          placeholder="Ex: Mathématiques, Physique-Chimie, Français..." 
                                          class="mt-1 block w-full" 
                                          required 
                                          autofocus />
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
                                    <option value="" disabled {{ old('niveau') ? '' : 'selected' }}>Sélectionnez un niveau</option>
                                    @foreach($niveaux as $niveau)
                                        <option value="{{ $niveau }}" {{ old('niveau') === $niveau ? 'selected' : '' }}>
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
                                                  :value="old('budget')" 
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

                        <!-- Description & Bouton Suggestion IA -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <x-input-label for="description" :value="__('Description détaillée du besoin')" />

                                <!-- Bouton suggestion IA -->
                                <button type="button" 
                                        id="btn-ai-suggest"
                                        onclick="suggestAIDescription()"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 bg-purple-50 dark:bg-purple-950/60 hover:bg-purple-100 dark:hover:bg-purple-900/80 px-3 py-1.5 rounded-lg transition-colors border border-purple-200 dark:border-purple-800/60">
                                    <span class="text-sm">✨</span>
                                    <span>Suggestion IA</span>
                                </button>
                            </div>

                            <textarea id="description" 
                                      name="description" 
                                      rows="5" 
                                      placeholder="Expliquez vos difficultés, objectifs d'apprentissage (préparation d'un examen, rattrapage, remise à niveau...) et rythme souhaité..."
                                      class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                      required>{{ old('description') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Minimum 20 caractères.</p>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('demandes.index') }}" 
                               class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button id="btn-submit-demande" class="px-6 py-2.5 rounded-xl text-sm">
                                {{ __('Soumettre la demande') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script pour la suggestion IA -->
    <script>
        function suggestAIDescription() {
            const matiere = document.getElementById('matiere').value.trim();
            const niveau = document.getElementById('niveau').value;
            const textarea = document.getElementById('description');
            const btn = document.getElementById('btn-ai-suggest');

            if (!matiere) {
                alert('Veuillez d\'abord saisir la matière souhaitée.');
                document.getElementById('matiere').focus();
                return;
            }

            const niveauText = niveau ? `en ${niveau}` : 'à mon niveau';
            
            // Simulation intelligente ou aide à la rédaction avant connexion API complète
            btn.disabled = true;
            btn.innerHTML = '<span>⏳ Génération...</span>';

            setTimeout(() => {
                const suggestion = `Je recherche un tuteur pédagogue et expérimenté pour un accompagnement régulier en ${matiere} (${niveauText}). Mon objectif est de consolider les bases, combler mes lacunes sur les chapitres clés et m'entraîner avec des exercices types pour réussir mes prochains examens. Rythme souhaité : 1 à 2 séances par semaine.`;
                textarea.value = suggestion;
                textarea.focus();
                btn.disabled = false;
                btn.innerHTML = '<span class="text-sm">✨</span><span>Suggestion appliquée !</span>';
                setTimeout(() => {
                    btn.innerHTML = '<span class="text-sm">✨</span><span>Suggestion IA</span>';
                }, 3000);
            }, 500);
        }
    </script>
</x-app-layout>
