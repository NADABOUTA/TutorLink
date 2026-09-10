<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">Mes propositions d'offres</h1>
                <p class="page-subtitle">Suivez l'état d'avancement de vos propositions faites aux apprenants.</p>
            </div>
            <a href="{{ route('demandes.index') }}" class="btn-primary flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Explorer les demandes
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Onglets de filtre --}}
        <div class="flex flex-wrap gap-2 pb-4 border-b border-slate-200">
            <a href="{{ route('offres.index') }}"
               class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ !request('statut') ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                Toutes ({{ $counts['all'] }})
            </a>
            <a href="{{ route('offres.index', ['statut' => 'en_attente']) }}"
               class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ request('statut') === 'en_attente' ? 'bg-amber-500 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                En attente ({{ $counts['en_attente'] }})
            </a>
            <a href="{{ route('offres.index', ['statut' => 'acceptee']) }}"
               class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ request('statut') === 'acceptee' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                Acceptées ({{ $counts['acceptee'] }})
            </a>
            <a href="{{ route('offres.index', ['statut' => 'refusee']) }}"
               class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ request('statut') === 'refusee' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                Non retenues ({{ $counts['refusee'] }})
            </a>
        </div>

        {{-- Liste des offres --}}
        @if($offres->isEmpty())
            <div class="card">
                <div class="empty-state py-12">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="empty-state-title">Aucune proposition trouvée</h3>
                    <p class="empty-state-desc">Vous n'avez pas encore envoyé d'offre correspondant à ce filtre.</p>
                    <a href="{{ route('demandes.index') }}" class="btn-primary mt-3">Parcourir les demandes disponibles</a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 stagger-children">
                @foreach($offres as $offre)
                    @php
                        $statuts = [
                            'en_attente' => ['badge-pending', 'En attente'],
                            'acceptee'   => ['badge-success', 'Acceptée'],
                            'refusee'    => ['badge-danger',  'Non retenue'],
                        ];
                        [$badgeCls, $badgeLabel] = $statuts[$offre->statut] ?? ['badge-info', $offre->statut];
                    @endphp
                    <div class="card flex flex-col justify-between transition-all duration-200 hover:shadow-md {{ $offre->statut === 'acceptee' ? 'border-emerald-300 bg-emerald-50/20' : '' }}">
                        <div>
                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <span class="badge-info text-xs">{{ $offre->demande->niveau }}</span>
                                    <h3 class="text-base font-bold text-slate-900 mt-1.5">{{ $offre->demande->matiere }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Élève : <strong class="text-slate-800">{{ $offre->demande->apprenant->name }}</strong></p>
                                </div>
                                <span class="{{ $badgeCls }} flex-shrink-0">{{ $badgeLabel }}</span>
                            </div>

                            {{-- Message --}}
                            <div class="mb-4">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Votre message :</span>
                                <p class="text-xs text-slate-600 leading-relaxed p-3 rounded-xl bg-slate-50 border border-slate-200 line-clamp-3">
                                    {{ $offre->message }}
                                </p>
                            </div>

                            {{-- Tarif --}}
                            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                <span class="text-xs text-slate-400 font-medium">Tarif proposé</span>
                                <span class="text-lg font-black text-blue-600">{{ number_format($offre->tarif_propose, 0) }} DH</span>
                            </div>

                            {{-- Coordonnées débloquées si acceptée --}}
                            @if($offre->statut === 'acceptee' && $offre->coordonnees_visibles)
                                <div class="mt-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 space-y-1.5">
                                    <p class="text-xs font-bold text-emerald-800 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        Coordonnées élève débloquées
                                    </p>
                                    <p class="text-xs text-slate-600"><span class="font-medium">Email :</span> <a href="mailto:{{ $offre->demande->apprenant->email }}" class="text-blue-600 hover:underline">{{ $offre->demande->apprenant->email }}</a></p>
                                    @if($offre->demande->apprenant->telephone)
                                        <p class="text-xs text-slate-800 font-semibold"><span class="font-medium text-slate-500">Tél :</span> <a href="tel:{{ $offre->demande->apprenant->telephone }}" class="hover:underline">{{ $offre->demande->apprenant->telephone }}</a></p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('demandes.show', $offre->demande) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                Voir la demande complète →
                            </a>
                            <span class="text-[11px] text-slate-400">{{ $offre->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $offres->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
