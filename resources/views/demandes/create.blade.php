<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Publier une demande de cours</h1>
                <p class="page-subtitle">Décrivez vos besoins pour recevoir des propositions de tuteurs qualifiés.</p>
            </div>
            <a href="{{ route('demandes.index') }}" class="btn-secondary btn-sm">← Retour</a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Note modération --}}
        <div class="alert-warning">
            <svg class="w-5 h-5 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <strong class="font-bold">Modération pédagogique :</strong>
                <p class="text-xs mt-0.5">Votre demande passera par une rapide modération administrative avant d'être transmise aux tuteurs certifiés.</p>
            </div>
        </div>

        <div class="card">
            <form method="POST" action="{{ route('demandes.store') }}" class="space-y-5">
                @csrf

                {{-- Matière --}}
                <div class="form-group mb-0">
                    <label for="matiere" class="form-label">Matière recherchée <span class="text-rose-500">*</span></label>
                    <input id="matiere" type="text" name="matiere" value="{{ old('matiere') }}" required autofocus
                           class="form-input" placeholder="Ex: Mathématiques, Physique-Chimie, Anglais, SVT...">
                    @error('matiere')
                        <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Niveau + Budget --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group mb-0">
                        <label for="niveau" class="form-label">Niveau scolaire <span class="text-rose-500">*</span></label>
                        <select id="niveau" name="niveau" required class="form-select">
                            <option value="" disabled {{ old('niveau') ? '' : 'selected' }}>Sélectionnez un niveau</option>
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau }}" {{ old('niveau') === $niveau ? 'selected' : '' }}>{{ $niveau }}</option>
                            @endforeach
                        </select>
                        @error('niveau')
                            <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="budget" class="form-label">Budget prévu (DH) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input id="budget" type="number" step="0.01" min="1" name="budget"
                                   value="{{ old('budget') }}" required class="form-input pr-12" placeholder="Ex: 200">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">DH</span>
                        </div>
                        @error('budget')
                            <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description + IA --}}
                <div class="form-group mb-0">
                    <div class="flex items-center justify-between mb-2">
                        <label for="description" class="form-label mb-0">Description détaillée <span class="text-rose-500">*</span></label>
                        <button type="button" id="btn-ai-suggest" onclick="suggestAIDescription()"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100 transition-colors shadow-xs">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Assistance rédactionnelle IA</span>
                        </button>
                    </div>
                    <textarea id="description" name="description" rows="5" required
                              class="form-textarea"
                              placeholder="Expliquez vos objectifs (préparation examen, soutien hebdomadaire...), votre disponibilité et rythme souhaité...">{{ old('description') }}</textarea>
                    <p class="text-xs mt-1 text-slate-400">Minimum 20 caractères recommandés.</p>
                    @error('description')
                        <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <a href="{{ route('demandes.index') }}" class="btn-secondary">Annuler</a>
                    <button type="submit" id="btn-submit-demande" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Publier ma demande
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function suggestAIDescription() {
            const matiereInput = document.getElementById('matiere');
            const niveauInput  = document.getElementById('niveau');
            const textarea     = document.getElementById('description');
            const btn          = document.getElementById('btn-ai-suggest');

            const matiere = matiereInput.value.trim();
            const niveau  = niveauInput.value;

            if (!matiere) {
                alert('Veuillez d\'abord saisir la matière souhaitée (ex: Mathématiques, Physique...).');
                matiereInput.focus();
                return;
            }

            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin inline-block mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Rédaction en cours...';

            try {
                const response = await fetch("{{ route('demandes.ai-suggest') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        matiere: matiere,
                        niveau: niveau,
                        contexte: textarea.value.trim()
                    })
                });

                const data = await response.json();

                if (response.ok && data.success && data.suggestion) {
                    textarea.value = data.suggestion;
                    textarea.focus();
                    btn.innerHTML = '<svg class="w-3.5 h-3.5 text-emerald-600 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Description générée';
                } else {
                    alert(data.message || 'Impossible de générer la suggestion pour le moment.');
                    btn.innerHTML = originalHtml;
                }
            } catch (error) {
                console.error('Erreur suggestion IA:', error);
                alert('Une erreur réseau est survenue lors de la génération.');
                btn.innerHTML = originalHtml;
            } finally {
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }, 3500);
            }
        }
    </script>
</x-app-layout>
