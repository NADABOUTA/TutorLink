<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
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
                <div class="flex items-center gap-3 flex-wrap mb-1">
                    <h1 class="page-title">{{ $demande->matiere }}</h1>
                    <span class="badge-info">{{ $demande->niveau }}</span>
                    <span class="{{ $badgeCls }}">{{ $badgeLabel }}</span>
                </div>
                <p class="page-subtitle">
                    Publiée par <strong class="text-white">{{ $demande->apprenant->name }}</strong>
                    · {{ $demande->created_at->translatedFormat('d F Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('demandes.index') }}" class="btn-secondary btn-sm">← Retour</a>
                @can('update', $demande)
                    <a href="{{ route('demandes.edit', $demande) }}" class="btn-secondary btn-sm">✏️ Modifier</a>
                @endcan
                @can('delete', $demande)
                    <form method="POST" action="{{ route('demandes.destroy', $demande) }}"
                          onsubmit="return confirm('Supprimer cette demande ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">Supprimer</button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Alerte statut --}}
        @if($demande->statut === 'en_moderation')
            <div class="alert-warning">
                <span class="text-lg">⏳</span>
                <div>
                    <strong>En cours d'examen</strong>
                    <p class="text-xs mt-0.5">Votre demande est en attente de validation par un modérateur. Elle sera visible aux tuteurs dès approbation.</p>
                </div>
            </div>
        @elseif($demande->statut === 'refusee')
            <div class="alert-danger">
                <span class="text-lg">❌</span>
                <div>
                    <strong>Demande refusée</strong>
                    <p class="text-xs mt-0.5"><strong>Motif :</strong> {{ $demande->motif_refus ?? 'Non conforme aux critères.' }}</p>
                    @can('update', $demande)
                        <a href="{{ route('demandes.edit', $demande) }}" class="text-xs underline mt-1 block">Modifier et soumettre à nouveau →</a>
                    @endcan
                </div>
            </div>
        @elseif($demande->statut === 'en_cours')
            <div class="alert-info">
                <span class="text-lg">🤝</span>
                <div>
                    <strong>Accompagnement en cours !</strong>
                    <p class="text-xs mt-0.5">Une offre a été acceptée. Les coordonnées mutuelles sont visibles ci-dessous.</p>
                </div>
            </div>
        @endif

        {{-- Détails --}}
        <div class="card">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider block mb-1" style="color:rgb(148,163,184);">Matière</span>
                    <span class="text-lg font-bold text-white">{{ $demande->matiere }}</span>
                </div>
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider block mb-1" style="color:rgb(148,163,184);">Niveau</span>
                    <span class="text-lg font-bold text-white">{{ $demande->niveau }}</span>
                </div>
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider block mb-1" style="color:rgb(148,163,184);">Budget</span>
                    <span class="text-2xl font-black" style="color: rgb(99,102,241);">{{ number_format($demande->budget, 0) }} DH</span>
                </div>
            </div>
            <div class="mt-6">
                <span class="text-xs font-semibold uppercase tracking-wider block mb-3" style="color:rgb(148,163,184);">Description</span>
                <div class="text-sm leading-relaxed p-5 rounded-xl whitespace-pre-line" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); color: rgb(203,213,225);">
                    {{ $demande->description }}
                </div>
            </div>
        </div>

        {{-- Proposer une offre (tuteur) --}}
        @if(Auth::user()->hasRole('tuteur') && $demande->statut === 'ouverte' && $demande->apprenant_id !== Auth::id())
            @php $monOffre = $demande->offres->where('tuteur_id', Auth::id())->first(); @endphp
            @if(!$monOffre)
                <div class="card" style="border-color: rgba(99,102,241,0.3); background: linear-gradient(135deg, rgba(99,102,241,0.05), rgba(168,85,247,0.03));">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(99,102,241,0.15);">✍️</div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Proposer votre offre</h2>
                            <p class="text-xs" style="color:rgb(148,163,184);">Présentez votre approche et votre tarif à cet apprenant.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('demandes.offres.store', $demande) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group" style="margin-bottom:0;">
                                <label for="tarif_propose" class="form-label">Tarif proposé (DH)</label>
                                <div class="relative">
                                    <input id="tarif_propose" type="number" step="0.01" min="1" name="tarif_propose"
                                           value="{{ old('tarif_propose', $demande->budget) }}" required
                                           class="form-input" style="padding-right:3.5rem;">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold" style="color:rgb(148,163,184);">DH</span>
                                </div>
                                @error('tarif_propose')
                                    <p class="text-xs mt-1" style="color:rgb(239,68,68);">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="message" class="form-label">Message de présentation</label>
                            <textarea id="message" name="message" rows="4" required class="form-textarea"
                                      placeholder="Bonjour, je suis professeur expérimenté en {{ $demande->matiere }}...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-xs mt-1" style="color:rgb(239,68,68);">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Envoyer mon offre
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="alert-info">
                    <span>✔️</span>
                    <span>Vous avez déjà soumis une offre de <strong>{{ number_format($monOffre->tarif_propose, 0) }} DH</strong> — Statut : <strong>{{ $monOffre->statut }}</strong></span>
                    <a href="{{ route('offres.index') }}" class="ml-auto text-xs underline flex-shrink-0">Voir mes offres →</a>
                </div>
            @endif
        @endif

        {{-- Liste des offres --}}
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-white">
                    Propositions reçues
                    <span class="ml-2 px-2 py-0.5 rounded-lg text-sm" style="background: rgba(99,102,241,0.15); color: rgb(99,102,241);">{{ $demande->offres->count() }}</span>
                </h2>
            </div>

            @if($demande->offres->isEmpty())
                <div class="empty-state py-10">
                    <div class="empty-state-icon">💬</div>
                    <p class="empty-state-title">Aucune proposition pour l'instant</p>
                    @if($demande->statut === 'ouverte' && Auth::user()->hasRole('tuteur'))
                        <p class="text-xs" style="color:rgb(99,102,241);">Soyez le premier à postuler !</p>
                    @endif
                </div>
            @else
                <div class="space-y-4">
                    @foreach($demande->offres as $offre)
                        <div class="rounded-2xl p-5 transition-all duration-200"
                             style="background: {{ $offre->statut === 'acceptee' ? 'rgba(34,197,94,0.08)' : ($offre->statut === 'refusee' ? 'rgba(255,255,255,0.02)' : 'rgba(255,255,255,0.04)') }}; border: 1px solid {{ $offre->statut === 'acceptee' ? 'rgba(34,197,94,0.3)' : 'rgba(255,255,255,0.06)' }}; opacity: {{ $offre->statut === 'refusee' ? '0.6' : '1' }};">

                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div class="flex-1 space-y-3">
                                    {{-- Tuteur info --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
                                             style="background: linear-gradient(135deg, rgb(99,102,241), rgb(168,85,247));">
                                            {{ strtoupper(substr($offre->tuteur->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="font-bold text-white">{{ $offre->tuteur->name }}</h3>
                                                @if($offre->tuteur->note_moyenne)
                                                    <span class="text-xs font-semibold" style="color: rgb(234,179,8);">★ {{ $offre->tuteur->note_moyenne }}/5</span>
                                                @endif
                                                @if($offre->statut === 'acceptee')
                                                    <span class="badge-success">🎉 Acceptée</span>
                                                @elseif($offre->statut === 'refusee')
                                                    <span class="badge-danger">Non retenue</span>
                                                @else
                                                    <span class="badge-pending">En attente</span>
                                                @endif
                                            </div>
                                            @if($offre->tuteur->matiere)
                                                <p class="text-xs" style="color:rgb(148,163,184);">Spécialité : {{ $offre->tuteur->matiere }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Message --}}
                                    <p class="text-sm leading-relaxed p-4 rounded-xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); color: rgb(203,213,225);">
                                        {{ $offre->message }}
                                    </p>

                                    {{-- Coordonnées si acceptée --}}
                                    @if($offre->coordonnees_visibles)
                                        <div class="p-4 rounded-xl" style="background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.25);">
                                            <p class="text-xs font-bold mb-3" style="color: rgb(34,197,94);">📞 Coordonnées débloquées</p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div class="p-3 rounded-lg" style="background: rgba(34,197,94,0.06); border: 1px solid rgba(34,197,94,0.15);">
                                                    <p class="text-xs font-semibold text-white mb-1">👨‍🏫 {{ $offre->tuteur->name }}</p>
                                                    <p class="text-xs" style="color:rgb(148,163,184);">📧 <a href="mailto:{{ $offre->tuteur->email }}" class="hover:underline" style="color:rgb(99,102,241);">{{ $offre->tuteur->email }}</a></p>
                                                    @if($offre->tuteur->telephone)
                                                        <p class="text-xs" style="color:rgb(148,163,184);">📱 <a href="tel:{{ $offre->tuteur->telephone }}" class="hover:underline text-white">{{ $offre->tuteur->telephone }}</a></p>
                                                    @endif
                                                </div>
                                                <div class="p-3 rounded-lg" style="background: rgba(34,197,94,0.06); border: 1px solid rgba(34,197,94,0.15);">
                                                    <p class="text-xs font-semibold text-white mb-1">🎓 {{ $demande->apprenant->name }}</p>
                                                    <p class="text-xs" style="color:rgb(148,163,184);">📧 <a href="mailto:{{ $demande->apprenant->email }}" class="hover:underline" style="color:rgb(99,102,241);">{{ $demande->apprenant->email }}</a></p>
                                                    @if($demande->apprenant->telephone)
                                                        <p class="text-xs" style="color:rgb(148,163,184);">📱 <a href="tel:{{ $demande->apprenant->telephone }}" class="hover:underline text-white">{{ $demande->apprenant->telephone }}</a></p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Tarif + Action --}}
                                <div class="flex flex-col items-end gap-3 flex-shrink-0">
                                    <div class="text-right">
                                        <span class="text-xs block" style="color:rgb(148,163,184);">Tarif proposé</span>
                                        <span class="text-2xl font-black" style="color: rgb(99,102,241);">{{ number_format($offre->tarif_propose, 0) }} DH</span>
                                    </div>
                                    @if(Auth::id() === $demande->apprenant_id && $demande->statut === 'ouverte' && $offre->statut === 'en_attente')
                                        <form method="POST" action="{{ route('offres.accepter', $offre) }}"
                                              onsubmit="return confirm('Accepter cette offre ? Les autres propositions seront refusées.');">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-success btn-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Accepter
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
