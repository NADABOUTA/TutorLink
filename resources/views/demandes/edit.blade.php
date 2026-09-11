<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div>
                <h1 class="page-title text-2xl font-black text-white font-display">Modifier la demande</h1>
                <p class="page-subtitle text-xs text-slate-400 mt-0.5">Mettez à jour les critères de votre besoin d'apprentissage.</p>
            </div>
            <a href="{{ route('demandes.show', $demande) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#101726] border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white text-xs font-bold transition">
                &larr; Retour aux détails
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        @if($demande->statut === 'refusee')
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/25 text-rose-300 text-xs flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 text-rose-400 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <strong class="font-bold text-rose-200">Demande refusée par la modération</strong>
                    <p class="text-xs text-slate-300 mt-0.5"><span class="font-semibold text-rose-300">Motif :</span> {{ $demande->motif_refus ?? 'Non spécifié.' }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">Vous pouvez modifier les informations et soumettre à nouveau pour validation.</p>
                </div>
            </div>
        @endif

        <div class="card p-8 rounded-3xl shadow-xl">
            <form method="POST" action="{{ route('demandes.update', $demande) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="form-group mb-0">
                    <label for="matiere" class="form-label">Matière <span class="text-amber-400">*</span></label>
                    <input id="matiere" type="text" name="matiere" value="{{ old('matiere', $demande->matiere) }}" required
                           class="form-input" placeholder="Ex: Mathématiques, Informatique, Anglais...">
                    @error('matiere')
                        <p class="text-xs mt-1.5 text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group mb-0">
                        <label for="niveau" class="form-label">Niveau d'études <span class="text-amber-400">*</span></label>
                        <select id="niveau" name="niveau" required class="form-select">
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau }}" {{ old('niveau', $demande->niveau) === $niveau ? 'selected' : '' }}>{{ $niveau }}</option>
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
                                   value="{{ old('budget', $demande->budget) }}" required
                                   class="form-input pr-12">
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
                    <textarea id="description" name="description" rows="5" required class="form-textarea">{{ old('description', $demande->description) }}</textarea>
                    @error('description')
                        <p class="text-xs mt-1.5 text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-800">
                    <a href="{{ route('demandes.show', $demande) }}" class="btn-secondary text-xs py-2.5 px-5">Annuler</a>
                    <button type="submit" class="btn-primary text-xs py-2.5 px-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Enregistrer les modifications</span>
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
