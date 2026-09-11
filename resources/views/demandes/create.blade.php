<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div>
                <h1 class="page-title text-2xl font-black text-white font-display">Nouvelle demande</h1>
                <p class="page-subtitle text-xs text-slate-400 mt-0.5">Décrivez vos besoins pour recevoir des propositions ciblées.</p>
            </div>
            <a href="{{ route('demandes.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#101726] border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white text-xs font-bold transition">
                &larr; Retour
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/25 text-amber-300 text-xs flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 text-amber-400 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <strong class="font-bold text-amber-200">Processus de Modération :</strong>
                <p class="text-xs text-slate-300 mt-0.5">Votre demande sera vérifiée par notre équipe avant d'être publiée pour les tuteurs certifiés.</p>
            </div>
        </div>

        <div class="card p-8 rounded-3xl shadow-xl">
            <form method="POST" action="{{ route('demandes.store') }}" class="space-y-5">
                @csrf

                <div class="form-group mb-0">
                    <label for="matiere" class="form-label">Matière <span class="text-amber-400">*</span></label>
                    <input id="matiere" type="text" name="matiere" value="{{ old('matiere') }}" required autofocus
                           class="form-input" placeholder="Ex: Mathématiques, Physique, Anglais...">
                    @error('matiere')
                        <p class="text-xs mt-1.5 text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group mb-0">
                        <label for="niveau" class="form-label">Niveau d'études <span class="text-amber-400">*</span></label>
                        <select id="niveau" name="niveau" required class="form-select">
                            <option value="" disabled {{ old('niveau') ? '' : 'selected' }}>Sélectionnez un niveau</option>
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau }}" {{ old('niveau') === $niveau ? 'selected' : '' }}>{{ $niveau }}</option>
                            @endforeach
                        </select>
                        @error('niveau')
                            <p class="text-xs mt-1.5 text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="budget" class="form-label">Budget Horaire (DH) <span class="text-amber-400">*</span></label>
                        <div class="relative">
                            <input id="budget" type="number" step="0.01" min="1" name="budget"
                                   value="{{ old('budget') }}" required class="form-input pr-12" placeholder="200">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-amber-400">DH</span>
                        </div>
                        @error('budget')
                            <p class="text-xs mt-1.5 text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-0">
                    <div class="flex items-center justify-between mb-2">
                        <label for="description" class="form-label mb-0">Description détaillée <span class="text-amber-400">*</span></label>
                        <button type="button" id="btn-ai-suggest" onclick="suggestAIDescription()"
                                class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl border border-amber-500/30 bg-amber-500/10 text-amber-400 hover:bg-amber-500/20 transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Assistance IA</span>
                        </button>
                    </div>
                    <textarea id="description" name="description" rows="5" required
                              class="form-textarea"
                              placeholder="Décrivez vos objectifs, chapitres prioritaires, disponibilités et rythme souhaité...">{{ old('description') }}</textarea>
                    <p class="text-[11px] mt-1.5 text-slate-400">Minimum 20 caractères recommandés pour obtenir des propositions ciblées.</p>
                    @error('description')
                        <p class="text-xs mt-1.5 text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-800">
                    <a href="{{ route('demandes.index') }}" class="btn-secondary text-xs py-2.5 px-5">Annuler</a>
                    <button type="submit" id="btn-submit-demande" class="btn-primary text-xs py-2.5 px-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Publier la demande</span>
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
                alert('Veuillez d\'abord saisir la matière souhaitée.');
                matiereInput.focus();
                return;
            }

            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Génération...';

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
                    btn.innerHTML = '<svg class="w-3.5 h-3.5 text-emerald-500 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Généré';
                } else {
                    alert(data.message || 'Impossible de générer la suggestion.');
                    btn.innerHTML = originalHtml;
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur réseau lors de la génération.');
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
