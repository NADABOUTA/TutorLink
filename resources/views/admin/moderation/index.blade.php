<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(234,179,8,0.15);">🛡️</div>
                <div>
                    <h1 class="page-title flex items-center gap-3">
                        Espace de Modération
                        @if($counts['en_moderation'] > 0)
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full animate-pulse" style="background: rgb(234,179,8); color: #000;">
                                {{ $counts['en_moderation'] }} en attente
                            </span>
                        @endif
                    </h1>
                    <p class="page-subtitle">Validez ou refusez les demandes soumises par les apprenants.</p>
                </div>
            </div>
            <span class="text-xs px-3 py-1.5 rounded-lg flex-shrink-0" style="background: rgba(234,179,8,0.1); border: 1px solid rgba(234,179,8,0.2); color: rgb(234,179,8);">🔒 Accès Administrateur</span>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Onglets --}}
        <div class="flex flex-wrap gap-2 pb-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            @php
                $tabs = [
                    'en_moderation' => ['⏳ À modérer', 'rgba(234,179,8,0.2)', 'rgba(234,179,8,0.4)', 'rgb(234,179,8)', $counts['en_moderation']],
                    'ouverte'       => ['✅ Ouvertes', 'rgba(34,197,94,0.2)', 'rgba(34,197,94,0.4)', 'rgb(34,197,94)', $counts['ouverte']],
                    'refusee'       => ['❌ Refusées', 'rgba(239,68,68,0.2)', 'rgba(239,68,68,0.4)', 'rgb(239,68,68)', $counts['refusee']],
                    'all'           => ['📋 Toutes', 'rgba(99,102,241,0.2)', 'rgba(99,102,241,0.4)', 'rgb(99,102,241)', $counts['all']],
                ];
            @endphp
            @foreach($tabs as $key => [$label, $bg, $border, $color, $count])
                <a href="{{ route('admin.moderation.index', ['statut' => $key]) }}"
                   class="px-4 py-2 text-sm font-semibold rounded-xl transition-all flex items-center gap-2"
                   style="{{ $status === $key ? "background: {$bg}; border: 1px solid {$border}; color: {$color};" : 'background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgb(148,163,184);' }}">
                    {{ $label }}
                    <span class="px-1.5 py-0.5 rounded-full text-xs" style="{{ $status === $key ? "background: {$bg}; color:{$color};" : 'background: rgba(255,255,255,0.05); color: rgb(148,163,184);' }}">{{ $count }}</span>
                </a>
            @endforeach
        </div>

        {{-- Liste --}}
        @if($demandes->isEmpty())
            <div class="card">
                <div class="empty-state">
                    <div class="empty-state-icon">✨</div>
                    <h3 class="empty-state-title">Aucune demande dans cette catégorie</h3>
                    <p class="empty-state-desc">Toutes les demandes ont été traitées.</p>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach($demandes as $demande)
                    @php
                        $statuts = [
                            'en_moderation' => ['badge-pending', '⏳ En modération'],
                            'ouverte'       => ['badge-success', '✅ Ouverte'],
                            'en_cours'      => ['badge-info',    '🔵 En cours'],
                            'terminee'      => ['badge-purple',  '✓ Terminée'],
                            'refusee'       => ['badge-danger',  '✕ Refusée'],
                        ];
                        [$badgeCls, $badgeLabel] = $statuts[$demande->statut] ?? ['badge-info', $demande->statut];
                    @endphp
                    <div x-data="{ openRefuse: false }" class="card">
                        {{-- En-tête --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <div class="flex items-center gap-3">
                                <h2 class="text-base font-bold text-white">#{{ $demande->id }} — {{ $demande->matiere }}</h2>
                                <span class="badge-info text-xs">{{ $demande->niveau }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="{{ $badgeCls }}">{{ $badgeLabel }}</span>
                                <span class="text-xs" style="color:rgb(148,163,184);">{{ $demande->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>

                        {{-- Infos --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 py-4 my-2 px-4 rounded-xl text-xs" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04);">
                            <div>
                                <span class="block mb-1 font-semibold" style="color:rgb(148,163,184);">Apprenant</span>
                                <strong class="text-white text-sm">{{ $demande->apprenant->name }}</strong>
                                <span class="block" style="color:rgb(148,163,184);">{{ $demande->apprenant->email }}</span>
                                @if($demande->apprenant->telephone)
                                    <span class="block" style="color:rgb(148,163,184);">📞 {{ $demande->apprenant->telephone }}</span>
                                @endif
                            </div>
                            <div>
                                <span class="block mb-1 font-semibold" style="color:rgb(148,163,184);">Budget</span>
                                <span class="text-lg font-bold" style="color: rgb(99,102,241);">{{ number_format($demande->budget, 0) }} DH</span>
                            </div>
                            <div>
                                <span class="block mb-1 font-semibold" style="color:rgb(148,163,184);">Offres reçues</span>
                                <span class="text-sm font-semibold text-white">{{ $demande->offres_count }} offre(s)</span>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <span class="text-xs font-semibold uppercase tracking-wider block mb-2" style="color:rgb(148,163,184);">Description</span>
                            <p class="text-sm leading-relaxed p-4 rounded-xl whitespace-pre-line" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); color: rgb(203,213,225);">
                                {{ $demande->description }}
                            </p>
                        </div>

                        {{-- Motif refus --}}
                        @if($demande->statut === 'refusee' && $demande->motif_refus)
                            <div class="alert-danger mb-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span><strong>Motif du refus :</strong> {{ $demande->motif_refus }}</span>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                            <a href="{{ route('demandes.show', $demande) }}" target="_blank" class="text-xs flex items-center gap-1 hover:underline" style="color: rgb(148,163,184); text-decoration:none;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Vue publique
                            </a>
                            <div class="flex items-center gap-3">
                                @if($demande->statut !== 'ouverte')
                                    <form method="POST" action="{{ route('admin.moderation.approuver', $demande) }}"
                                          onsubmit="return confirm('Approuver cette demande et la rendre visible aux tuteurs ?');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-success btn-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Approuver
                                        </button>
                                    </form>
                                @endif
                                @if($demande->statut !== 'refusee')
                                    <button type="button" @click="openRefuse = !openRefuse" class="btn-danger btn-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Refuser
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Formulaire refus --}}
                        <div x-show="openRefuse" x-cloak x-transition
                             class="mt-4 p-4 rounded-xl" style="background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.2);">
                            <form method="POST" action="{{ route('admin.moderation.refuser', $demande) }}">
                                @csrf @method('PATCH')
                                <label for="motif_refus_{{ $demande->id }}" class="form-label" style="color:rgb(239,68,68);">Motif du refus (transmis à l'apprenant)</label>
                                <textarea id="motif_refus_{{ $demande->id }}" name="motif_refus" rows="3" required
                                          class="form-textarea"
                                          placeholder="Ex: Description insuffisante, budget irréaliste..."></textarea>
                                <div class="flex justify-end gap-2 mt-3">
                                    <button type="button" @click="openRefuse = false" class="btn-secondary btn-sm">Annuler</button>
                                    <button type="submit" class="btn-danger btn-sm">Confirmer le refus</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $demandes->links() }}</div>
        @endif
    </div>
</x-app-layout>
