<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="page-title text-2xl font-black text-white font-display">Mes propositions d'offres</h1>
                <p class="page-subtitle text-xs text-slate-400 mt-0.5">Suivez l'état d'avancement de vos propositions faites aux apprenants.</p>
            </div>
            <a href="{{ route('demandes.index') }}" class="btn-primary text-xs py-2.5 px-5 flex-shrink-0 inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Explorer les demandes</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Onglets de filtre --}}
        <div class="flex flex-wrap gap-2 pb-4 border-b border-slate-800">
            <a href="{{ route('offres.index') }}"
               class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ !request('statut') ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/20' : 'bg-[#101726] text-slate-400 border border-slate-800 hover:text-white hover:border-slate-700' }}">
                Toutes ({{ $counts['all'] }})
            </a>
            <a href="{{ route('offres.index', ['statut' => 'en_attente']) }}"
               class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ request('statut') === 'en_attente' ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/20' : 'bg-[#101726] text-slate-400 border border-slate-800 hover:text-white hover:border-slate-700' }}">
                En attente ({{ $counts['en_attente'] }})
            </a>
            <a href="{{ route('offres.index', ['statut' => 'acceptee']) }}"
               class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ request('statut') === 'acceptee' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'bg-[#101726] text-slate-400 border border-slate-800 hover:text-white hover:border-slate-700' }}">
                Acceptées ({{ $counts['acceptee'] }})
            </a>
            <a href="{{ route('offres.index', ['statut' => 'refusee']) }}"
               class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ request('statut') === 'refusee' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/20' : 'bg-[#101726] text-slate-400 border border-slate-800 hover:text-white hover:border-slate-700' }}">
                Non retenues ({{ $counts['refusee'] }})
            </a>
        </div>

        {{-- Liste des offres --}}
        @if($offres->isEmpty())
            <div class="card p-12 rounded-3xl text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-[#090D16] border border-slate-800 flex items-center justify-center text-slate-500">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-base font-bold text-white">Aucune proposition trouvée</h3>
                <p class="text-xs text-slate-400 mt-1">Vous n'avez pas encore envoyé d'offre correspondant à ce filtre.</p>
                <div class="mt-4">
                    <a href="{{ route('demandes.index') }}" class="btn-primary text-xs py-2.5 px-6 inline-flex items-center gap-2">Parcourir les demandes disponibles</a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($offres as $offre)
                    @php
                        $statuts = [
                            'en_attente' => ['bg-amber-500/10 text-amber-400 border-amber-500/20', 'En attente'],
                            'acceptee'   => ['bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'Acceptée'],
                            'refusee'    => ['bg-rose-500/10 text-rose-400 border-rose-500/20',  'Non retenue'],
                        ];
                        [$badgeCls, $badgeLabel] = $statuts[$offre->statut] ?? ['bg-slate-800 text-slate-300 border-slate-700', $offre->statut];
                    @endphp
                    <div class="card p-6 rounded-3xl flex flex-col justify-between transition-all duration-300 hover:border-slate-700 hover:-translate-y-1 shadow-xl {{ $offre->statut === 'acceptee' ? 'border-emerald-500/30 bg-gradient-to-br from-[#101726] to-emerald-950/20' : '' }}">
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20">{{ $offre->demande->niveau }}</span>
                                    <h3 class="text-base font-black text-white mt-1.5 font-display">{{ $offre->demande->matiere }}</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Élève : <strong class="text-white">{{ $offre->demande->apprenant->name }}</strong></p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $badgeCls }} flex-shrink-0">{{ $badgeLabel }}</span>
                            </div>

                            <div class="mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Votre message :</span>
                                <p class="text-xs text-slate-300 leading-relaxed p-3.5 rounded-2xl bg-[#090D16] border border-slate-800 line-clamp-3 font-normal">
                                    {{ $offre->message }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-slate-800">
                                <span class="text-xs text-slate-400 font-medium">Tarif proposé</span>
                                <span class="text-lg font-black text-amber-400 font-display">{{ number_format($offre->tarif_propose, 0) }} DH</span>
                            </div>

                            @if($offre->statut === 'acceptee' && $offre->coordonnees_visibles)
                                @php
                                    $cleanTelApprenant = preg_replace('/[^0-9]/', '', $offre->demande->apprenant->telephone ?? '');
                                    if (str_starts_with($cleanTelApprenant, '0')) {
                                        $cleanTelApprenant = '212' . substr($cleanTelApprenant, 1);
                                    }
                                @endphp
                                <div class="mt-4 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 space-y-2">
                                    <p class="text-xs font-black text-emerald-400 flex items-center gap-1.5 uppercase tracking-wider">
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        Coordonnées élève débloquées
                                    </p>
                                    <p class="text-xs text-slate-300"><span class="font-medium text-slate-400">Email :</span> <a href="mailto:{{ $offre->demande->apprenant->email }}" class="text-amber-400 hover:underline">{{ $offre->demande->apprenant->email }}</a></p>
                                    @if($offre->demande->apprenant->telephone)
                                        <div class="flex items-center gap-2 pt-1 flex-wrap">
                                            <a href="tel:{{ $offre->demande->apprenant->telephone }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#090D16] border border-slate-700 text-xs text-slate-200 hover:border-slate-600">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                Appeler
                                            </a>
                                            @if($cleanTelApprenant)
                                                <a href="https://wa.me/{{ $cleanTelApprenant }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#25D366] hover:bg-[#20ba59] text-xs font-bold text-white transition">
                                                    WhatsApp
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="mt-5 pt-3 border-t border-slate-800 flex items-center justify-between">
                            <a href="{{ route('demandes.show', $offre->demande) }}" class="text-xs font-bold text-amber-400 hover:text-amber-300 transition">
                                Voir la demande complète &rarr;
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