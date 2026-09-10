<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-amber-700 bg-amber-100 font-bold text-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h1 class="page-title flex items-center gap-3">
                        Espace de Modération
                        @if($counts['en_moderation'] > 0)
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-amber-500 text-white animate-pulse">
                                {{ $counts['en_moderation'] }} en attente
                            </span>
                        @endif
                    </h1>
                    <p class="page-subtitle">Examinez et validez les demandes d'apprentissage avant diffusion.</p>
                </div>
            </div>
            <span class="text-xs px-3 py-1.5 rounded-lg font-bold bg-amber-50 border border-amber-200 text-amber-800 flex-shrink-0 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Accès Administrateur
            </span>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Onglets --}}
        <div class="flex flex-wrap gap-2 pb-4 border-b border-slate-200">
            @php
                $tabs = [
                    'en_attente_moderation' => ['À modérer', 'bg-amber-500 text-white', $counts['en_attente_moderation']],
                    'ouverte'               => ['Validées', 'bg-emerald-600 text-white', $counts['ouverte']],
                    'refusee'               => ['Refusées', 'bg-rose-600 text-white', $counts['refusee']],
                    'all'                   => ['Toutes', 'bg-blue-600 text-white', $counts['all']],
                ];
            @endphp
            @foreach($tabs as $key => [$label, $activeClass, $count])
                @php
                    $isActive = ($status === $key) || ($key === 'en_attente_moderation' && $status === 'en_moderation');
                @endphp
                <a href="{{ route('admin.moderation.index', ['statut' => $key]) }}"
                   class="px-4 py-2 text-xs font-bold rounded-xl transition-all flex items-center gap-2 {{ $isActive ? $activeClass . ' shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    <span>{{ $label }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] {{ $isActive ? 'bg-black/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $count }}</span>
                </a>
            @endforeach
        </div>

        {{-- Liste des demandes --}}
        @if($demandes->isEmpty())
            <div class="card">
                <div class="empty-state py-12">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="empty-state-title">Aucune demande dans cette catégorie</h3>
                    <p class="empty-state-desc">Toutes les demandes de cette liste ont été traitées.</p>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach($demandes as $demande)
                    @php
                        $statuts = [
                            'en_attente_moderation' => ['badge-pending', 'En modération'],
                            'en_moderation'         => ['badge-pending', 'En modération'],
                            'ouverte'               => ['badge-success', 'Validée'],
                            'en_cours'              => ['badge-info',    'En cours'],
                            'terminee'              => ['badge-purple',  'Terminée'],
                            'refusee'               => ['badge-danger',  'Refusée'],
                        ];
                        [$badgeCls, $badgeLabel] = $statuts[$demande->statut] ?? ['badge-info', $demande->statut];
                    @endphp
                    <div x-data="{ openRefuse: false }" class="card">
                        {{-- En-tête --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <h2 class="text-base font-bold text-slate-900">#{{ $demande->id }} — {{ $demande->matiere }}</h2>
                                <span class="badge-info text-xs">{{ $demande->niveau }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="{{ $badgeCls }}">{{ $badgeLabel }}</span>
                                <span class="text-xs text-slate-400">{{ $demande->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>

                        {{-- Infos --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 py-4 my-2 px-4 rounded-xl text-xs bg-slate-50 border border-slate-200">
                            <div>
                                <span class="block mb-1 font-bold text-slate-400 uppercase tracking-wider">Apprenant</span>
                                <strong class="text-slate-900 text-sm">{{ $demande->apprenant->name }}</strong>
                                <span class="block text-slate-500">{{ $demande->apprenant->email }}</span>
                                @if($demande->apprenant->telephone)
                                    <span class="block text-slate-500">Tél : {{ $demande->apprenant->telephone }}</span>
                                @endif
                            </div>
                            <div>
                                <span class="block mb-1 font-bold text-slate-400 uppercase tracking-wider">Budget</span>
                                <span class="text-lg font-black text-blue-600">{{ number_format($demande->budget, 0) }} DH</span>
                            </div>
                            <div>
                                <span class="block mb-1 font-bold text-slate-400 uppercase tracking-wider">Propositions</span>
                                <span class="text-sm font-bold text-slate-700">{{ $demande->offres_count }} offre(s)</span>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider block mb-1 text-slate-400">Description :</span>
                            <p class="text-sm leading-relaxed p-4 rounded-xl whitespace-pre-line bg-white border border-slate-200 text-slate-700">
                                {{ $demande->description }}
                            </p>
                        </div>

                        {{-- Motif refus --}}
                        @if($demande->statut === 'refusee' && $demande->motif_refus)
                            <div class="alert-danger mb-4">
                                <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-xs"><strong class="font-bold">Motif du refus :</strong> {{ $demande->motif_refus }}</span>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-100">
                            <a href="{{ route('demandes.show', $demande) }}" target="_blank" class="text-xs font-semibold flex items-center gap-1 text-slate-500 hover:text-blue-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Voir la demande
                            </a>
                            <div class="flex items-center gap-2">
                                @if($demande->statut !== 'ouverte')
                                    <form method="POST" action="{{ route('admin.moderation.approuver', $demande) }}"
                                          onsubmit="return confirm('Valider cette demande et la rendre visible aux tuteurs ?');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-success btn-sm">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Approuver
                                        </button>
                                    </form>
                                @endif
                                @if($demande->statut !== 'refusee')
                                    <button type="button" @click="openRefuse = !openRefuse" class="btn-danger btn-sm">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Refuser
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Formulaire refus --}}
                        <div x-show="openRefuse" x-cloak x-transition
                             class="mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200">
                            <form method="POST" action="{{ route('admin.moderation.refuser', $demande) }}">
                                @csrf @method('PATCH')
                                <label for="motif_refus_{{ $demande->id }}" class="form-label text-rose-800">Motif du refus (notifié à l'apprenant)</label>
                                <textarea id="motif_refus_{{ $demande->id }}" name="motif_refus" rows="3" required
                                          class="form-textarea"
                                          placeholder="Ex: Préciser le chapitre abordé, budget non conforme..."></textarea>
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
