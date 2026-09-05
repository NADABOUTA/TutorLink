<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">Mes propositions d'offres</h1>
                <p class="page-subtitle">Suivez l'état de vos propositions faites aux apprenants.</p>
            </div>
            <a href="{{ route('demandes.index') }}" class="btn-primary flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Demandes ouvertes
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Onglets --}}
        <div class="flex flex-wrap gap-2 pb-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <a href="{{ route('offres.index') }}"
               class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ !request('statut') ? 'text-white' : '' }}"
               style="{{ !request('statut') ? 'background: rgba(99,102,241,0.2); border: 1px solid rgba(99,102,241,0.4); color:white;' : 'background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgb(148,163,184);' }}">
                Toutes ({{ $counts['all'] }})
            </a>
            <a href="{{ route('offres.index', ['statut' => 'en_attente']) }}"
               class="px-4 py-2 text-sm font-semibold rounded-xl transition-all"
               style="{{ request('statut') === 'en_attente' ? 'background: rgba(234,179,8,0.2); border: 1px solid rgba(234,179,8,0.4); color:rgb(234,179,8);' : 'background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgb(148,163,184);' }}">
                ⏳ En attente ({{ $counts['en_attente'] }})
            </a>
            <a href="{{ route('offres.index', ['statut' => 'acceptee']) }}"
               class="px-4 py-2 text-sm font-semibold rounded-xl transition-all"
               style="{{ request('statut') === 'acceptee' ? 'background: rgba(34,197,94,0.2); border: 1px solid rgba(34,197,94,0.4); color:rgb(34,197,94);' : 'background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgb(148,163,184);' }}">
                🎉 Acceptées ({{ $counts['acceptee'] }})
            </a>
            <a href="{{ route('offres.index', ['statut' => 'refusee']) }}"
               class="px-4 py-2 text-sm font-semibold rounded-xl transition-all"
               style="{{ request('statut') === 'refusee' ? 'background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.4); color:rgb(239,68,68);' : 'background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgb(148,163,184);' }}">
                ❌ Non retenues ({{ $counts['refusee'] }})
            </a>
        </div>

        {{-- Liste --}}
        @if($offres->isEmpty())
            <div class="card">
                <div class="empty-state">
                    <div class="empty-state-icon">💼</div>
                    <h3 class="empty-state-title">Aucune offre trouvée</h3>
                    <p class="empty-state-desc">Vous n'avez pas encore envoyé d'offre correspondant à ces critères.</p>
                    <a href="{{ route('demandes.index') }}" class="btn-primary">Explorer les demandes</a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 stagger-children">
                @foreach($offres as $offre)
                    @php
                        $statuts = [
                            'en_attente' => ['badge-pending', '⏳ En attente'],
                            'acceptee'   => ['badge-success', '🎉 Acceptée'],
                            'refusee'    => ['badge-danger',  '✕ Non retenue'],
                        ];
                        [$badgeCls, $badgeLabel] = $statuts[$offre->statut] ?? ['badge-info', $offre->statut];
                    @endphp
                    <div class="card flex flex-col" style="{{ $offre->statut === 'acceptee' ? 'border-color: rgba(34,197,94,0.3); background: rgba(34,197,94,0.05);' : ($offre->statut === 'refusee' ? 'opacity:0.7;' : '') }}">
                        <div class="flex-1">
                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div>
                                    <span class="badge-info text-xs px-2 py-0.5 rounded-lg" style="font-size:11px;">{{ $offre->demande->niveau }}</span>
                                    <h3 class="text-lg font-bold text-white mt-1">{{ $offre->demande->matiere }}</h3>
                                    <p class="text-xs" style="color:rgb(148,163,184);">Pour : <strong class="text-white">{{ $offre->demande->apprenant->name }}</strong></p>
                                </div>
                                <span class="{{ $badgeCls }}" style="white-space:nowrap; flex-shrink:0;">{{ $badgeLabel }}</span>
                            </div>

                            {{-- Message --}}
                            <div class="mb-4">
                                <span class="text-xs font-semibold uppercase block mb-1" style="color:rgb(148,163,184);">Votre message :</span>
                                <p class="text-xs leading-relaxed p-3 rounded-xl line-clamp-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); color: rgb(203,213,225);">
                                    {{ $offre->message }}
                                </p>
                            </div>

                            {{-- Tarif --}}
                            <div class="flex items-center justify-between pt-3" style="border-top: 1px solid rgba(255,255,255,0.06);">
                                <span class="text-xs" style="color:rgb(148,163,184);">Tarif proposé</span>
                                <span class="text-lg font-bold" style="color: rgb(99,102,241);">{{ number_format($offre->tarif_propose, 0) }} DH</span>
                            </div>

                            {{-- Coordonnées si acceptée --}}
                            @if($offre->statut === 'acceptee' && $offre->coordonnees_visibles)
                                <div class="mt-4 p-3 rounded-xl" style="background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2);">
                                    <p class="text-xs font-bold mb-2" style="color: rgb(34,197,94);">📞 Contact apprenant débloqué</p>
                                    <p class="text-xs" style="color:rgb(148,163,184);">📧 <a href="mailto:{{ $offre->demande->apprenant->email }}" class="hover:underline" style="color: rgb(99,102,241);">{{ $offre->demande->apprenant->email }}</a></p>
                                    @if($offre->demande->apprenant->telephone)
                                        <p class="text-xs mt-0.5 text-white">📱 <a href="tel:{{ $offre->demande->apprenant->telephone }}" class="hover:underline">{{ $offre->demande->apprenant->telephone }}</a></p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.04);">
                            <span class="text-xs" style="color:rgb(148,163,184);">{{ $offre->created_at->diffForHumans() }}</span>
                            <a href="{{ route('demandes.show', $offre->demande_id) }}" class="text-xs font-semibold hover:underline" style="color: rgb(99,102,241); text-decoration:none;">Voir la demande →</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $offres->links() }}</div>
        @endif
    </div>
</x-app-layout>
