<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Modifier la demande ✏️</h1>
                <p class="page-subtitle">Mettez à jour les informations de votre demande de cours.</p>
            </div>
            <a href="{{ route('demandes.show', $demande) }}" class="btn-secondary btn-sm">← Retour</a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">

        @if($demande->statut === 'refusee')
            <div class="alert-danger mb-6">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <strong>Demande précédemment refusée</strong>
                    <p class="text-xs mt-0.5"><strong>Motif :</strong> {{ $demande->motif_refus ?? 'Non spécifié.' }}</p>
                    <p class="text-xs mt-1" style="opacity:0.7;">En sauvegardant, votre demande sera renvoyée en modération.</p>
                </div>
            </div>
        @endif

        <div class="card">
            <form method="POST" action="{{ route('demandes.update', $demande) }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Matière --}}
                <div class="form-group">
                    <label for="matiere" class="form-label">Matière recherchée <span style="color:rgb(239,68,68)">*</span></label>
                    <input id="matiere" type="text" name="matiere" value="{{ old('matiere', $demande->matiere) }}" required
                           class="form-input" placeholder="Ex: Mathématiques, Anglais...">
                    @error('matiere')
                        <p class="text-xs mt-1" style="color:rgb(239,68,68);">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Niveau + Budget --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group" style="margin-bottom:0;">
                        <label for="niveau" class="form-label">Niveau scolaire <span style="color:rgb(239,68,68)">*</span></label>
                        <select id="niveau" name="niveau" required class="form-select">
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau }}" {{ old('niveau', $demande->niveau) === $niveau ? 'selected' : '' }}>{{ $niveau }}</option>
                            @endforeach
                        </select>
                        @error('niveau')
                            <p class="text-xs mt-1" style="color:rgb(239,68,68);">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label for="budget" class="form-label">Budget (DH) <span style="color:rgb(239,68,68)">*</span></label>
                        <div class="relative">
                            <input id="budget" type="number" step="0.01" min="1" name="budget"
                                   value="{{ old('budget', $demande->budget) }}" required
                                   class="form-input" style="padding-right:3.5rem;">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold" style="color:rgb(148,163,184);">DH</span>
                        </div>
                        @error('budget')
                            <p class="text-xs mt-1" style="color:rgb(239,68,68);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label for="description" class="form-label">Description <span style="color:rgb(239,68,68)">*</span></label>
                    <textarea id="description" name="description" rows="5" required class="form-textarea">{{ old('description', $demande->description) }}</textarea>
                    @error('description')
                        <p class="text-xs mt-1" style="color:rgb(239,68,68);">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                    <a href="{{ route('demandes.show', $demande) }}" class="btn-secondary">Annuler</a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
