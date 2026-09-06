<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                @php
                    $statuts = [
                        'en_attente_moderation' => ['badge-pending', '⏳ En attente de modération'],
                        'en_moderation'         => ['badge-pending', '⏳ En attente de modération'],
                        'ouverte'               => ['badge-success', '✅ Ouverte aux professeurs'],
                        'en_cours'              => ['badge-info',    '🔵 En cours'],
                        'terminee'              => ['badge-purple',  '✓ Terminée'],
                        'refusee'               => ['badge-danger',  '✕ Refusée'],
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
        @if(in_array($demande->statut, ['en_attente_moderation', 'en_moderation']))
            <div class="alert-warning">
                <span class="text-lg">⏳</span>
                <div>
                    <strong>Demande en attente de modération</strong>
                    <p class="text-xs mt-0.5">Votre demande est en cours d'examen par un administrateur. Dès qu'elle sera validée, elle deviendra visible et les professeurs qualifiés pourront vous soumettre leurs offres.</p>
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
            <div class="alert-info flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-lg">🤝</span>
                    <div>
                        <strong>Accompagnement en cours !</strong>
                        <p class="text-xs mt-0.5">Une offre a été acceptée. Les coordonnées mutuelles sont visibles ci-dessous.</p>
                    </div>
                </div>
                @if(Auth::id() === $demande->apprenant_id)
                    <form method="POST" action="{{ route('demandes.terminer', $demande) }}" onsubmit="return confirm('Confirmez-vous que les séances de tutorat sont terminées ?');">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-primary btn-sm">
                            ✓ Marquer comme terminée
                        </button>
                    </form>
                @endif
            </div>
        @elseif($demande->statut === 'terminee')
            <div class="alert-success">
                <span class="text-lg">🎉</span>
                <div>
                    <strong>Demande terminée !</strong>
                    <p class="text-xs mt-0.5">Cet accompagnement pédagogique est achevé avec succès.</p>
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

        @php
            $offreRetenue = $demande->offres->where('statut', 'acceptee')->first();
        @endphp

        {{-- Section Avis / Évaluation (Phase 8) --}}
        @if($offreRetenue)
            @if($demande->avis)
                {{-- Avis déjà déposé --}}
                <div class="card" style="border-color: rgba(234,179,8,0.3); background: linear-gradient(135deg, rgba(234,179,8,0.06), rgba(99,102,241,0.03));">
                    <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(234,179,8,0.15);">
                                ⭐
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-base">Évaluation de la prestation</h3>
                                <p class="text-xs" style="color:rgb(148,163,184);">
                                    Avis déposé par {{ $demande->apprenant->name }} pour {{ $offreRetenue->tuteur->name }} · {{ $demande->avis->created_at->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5" style="color: {{ $i <= $demande->avis->note ? '#f59e0b' : '#52525b' }};" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="font-bold text-white ml-2 text-sm">{{ $demande->avis->note }}/5</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl text-sm leading-relaxed" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); color: rgb(226,232,240);">
                        "{{ $demande->avis->commentaire }}"
                    </div>
                </div>
            @elseif(Auth::id() === $demande->apprenant_id && in_array($demande->statut, ['en_cours', 'terminee']))
                {{-- Formulaire de notation 1-5 + commentaire --}}
                <div class="card" style="border-color: rgba(234,179,8,0.3); background: linear-gradient(135deg, rgba(234,179,8,0.06), rgba(99,102,241,0.04));"
                     x-data="{ rating: 5, hoverRating: 0 }">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(234,179,8,0.15);">
                            ⭐
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-lg">Évaluer votre tuteur ({{ $offreRetenue->tuteur->name }})</h3>
                            <p class="text-xs" style="color:rgb(148,163,184);">Partagez votre avis pour aider la communauté et valoriser le travail du tuteur.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('avis.store', $demande) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="note" :value="rating">

                        {{-- Sélecteur étoiles interactif --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Votre note globale</label>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                        <button type="button"
                                                @click="rating = star"
                                                @mouseenter="hoverRating = star"
                                                @mouseleave="hoverRating = 0"
                                                class="p-1 focus:outline-none transition-transform hover:scale-125 cursor-pointer">
                                            <svg class="w-8 h-8 transition-colors duration-150"
                                                 :style="(hoverRating ? hoverRating >= star : rating >= star) ? 'color: #f59e0b;' : 'color: #52525b;'"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                                <span class="text-sm font-bold text-white" x-text="(hoverRating || rating) + ' / 5 étoiles'"></span>
                            </div>
                            @error('note')
                                <p class="text-xs mt-1" style="color:rgb(239,68,68);">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Commentaire --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="commentaire" class="form-label">Votre commentaire d'évaluation</label>
                            <textarea id="commentaire" name="commentaire" rows="3" required class="form-textarea"
                                      placeholder="Pédagogie, ponctualité, clarté des explications... Partagez votre retour d'expérience !">{{ old('commentaire') }}</textarea>
                            @error('commentaire')
                                <p class="text-xs mt-1" style="color:rgb(239,68,68);">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, rgb(234,179,8), rgb(249,115,22)); border-color: rgba(234,179,8,0.5);">
                                ⭐ Soumettre mon avis
                            </button>
                        </div>
                    </form>
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
                                                    <span class="text-xs font-semibold" style="color: rgb(234,179,8);">★ {{ $offre->tuteur->note_moyenne }}/5 ({{ $offre->tuteur->avisRecus->count() }} avis)</span>
                                                @endif
                                                @if($offre->statut === 'acceptee')
                                                    <span class="badge-success">🎉 Retenue</span>
                                                @elseif($offre->statut === 'refusee')
                                                    <span class="badge-danger">Non retenue</span>
                                                @else
                                                    <span class="badge-pending">En attente</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-3 flex-wrap mt-0.5">
                                                @if($offre->tuteur->matiere)
                                                    <span class="text-xs" style="color:rgb(148,163,184);">📚 Spécialité : {{ $offre->tuteur->matiere }}</span>
                                                @endif
                                                @if($offre->tuteur->tarif_horaire)
                                                    <span class="text-xs" style="color:rgb(148,163,184);">⏱️ Tarif usuel : {{ number_format($offre->tuteur->tarif_horaire, 0) }} DH/h</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Bio du professeur si renseignée --}}
                                    @if($offre->tuteur->bio)
                                        <div class="p-3 rounded-xl text-xs leading-relaxed" style="background: rgba(168,85,247,0.04); border: 1px solid rgba(168,85,247,0.15); color: rgb(216,180,254);">
                                            <strong>À propos du tuteur :</strong> {{ $offre->tuteur->bio }}
                                        </div>
                                    @endif

                                    {{-- Message de proposition --}}
                                    <p class="text-sm leading-relaxed p-4 rounded-xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); color: rgb(203,213,225);">
                                        {{ $offre->message }}
                                    </p>

                                    {{-- Coordonnées débloquées si acceptée --}}
                                    @if($offre->coordonnees_visibles)
                                        @php
                                            $cleanTelTuteur = preg_replace('/[^0-9]/', '', $offre->tuteur->telephone ?? '');
                                            if (str_starts_with($cleanTelTuteur, '0')) {
                                                $cleanTelTuteur = '212' . substr($cleanTelTuteur, 1);
                                            }

                                            $cleanTelApprenant = preg_replace('/[^0-9]/', '', $demande->apprenant->telephone ?? '');
                                            if (str_starts_with($cleanTelApprenant, '0')) {
                                                $cleanTelApprenant = '212' . substr($cleanTelApprenant, 1);
                                            }
                                        @endphp
                                        <div class="p-4 rounded-xl" style="background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.25);">
                                            <p class="text-xs font-bold mb-3 flex items-center gap-1.5" style="color: rgb(34,197,94);">
                                                <span>📞</span> Coordonnées de contact débloquées
                                            </p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                {{-- Fiche Tuteur --}}
                                                <div class="p-3 rounded-lg space-y-2" style="background: rgba(34,197,94,0.06); border: 1px solid rgba(34,197,94,0.15);">
                                                    <p class="text-xs font-semibold text-white">👨‍🏫 Tuteur : {{ $offre->tuteur->name }}</p>
                                                    <p class="text-xs" style="color:rgb(148,163,184);">📧 <a href="mailto:{{ $offre->tuteur->email }}" class="hover:underline text-white">{{ $offre->tuteur->email }}</a></p>
                                                    @if($offre->tuteur->telephone)
                                                        <div class="flex items-center gap-2 pt-1 flex-wrap">
                                                            <a href="tel:{{ $offre->tuteur->telephone }}" class="btn-secondary btn-sm text-[11px] py-1 px-2.5">
                                                                📞 Appeler
                                                            </a>
                                                            @if($cleanTelTuteur)
                                                                <a href="https://wa.me/{{ $cleanTelTuteur }}" target="_blank" class="btn-success btn-sm text-[11px] py-1 px-2.5">
                                                                    💬 WhatsApp
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Fiche Apprenant --}}
                                                <div class="p-3 rounded-lg space-y-2" style="background: rgba(34,197,94,0.06); border: 1px solid rgba(34,197,94,0.15);">
                                                    <p class="text-xs font-semibold text-white">🎓 Apprenant : {{ $demande->apprenant->name }}</p>
                                                    <p class="text-xs" style="color:rgb(148,163,184);">📧 <a href="mailto:{{ $demande->apprenant->email }}" class="hover:underline text-white">{{ $demande->apprenant->email }}</a></p>
                                                    @if($demande->apprenant->telephone)
                                                        <div class="flex items-center gap-2 pt-1 flex-wrap">
                                                            <a href="tel:{{ $demande->apprenant->telephone }}" class="btn-secondary btn-sm text-[11px] py-1 px-2.5">
                                                                📞 Appeler
                                                            </a>
                                                            @if($cleanTelApprenant)
                                                                <a href="https://wa.me/{{ $cleanTelApprenant }}" target="_blank" class="btn-success btn-sm text-[11px] py-1 px-2.5">
                                                                    💬 WhatsApp
                                                                </a>
                                                            @endif
                                                        </div>
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
                                              onsubmit="return confirm('Accepter cette offre ? Les coordonnées de contact mutuelles seront immédiatement débloquées.');">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-success btn-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Accepter cette offre
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

        {{-- Section Discussion & Commentaires (Échanges Apprenant / Tuteur) --}}
        <div class="card" id="espace-commentaires">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg" style="background: rgba(99,102,241,0.15);">
                        💬
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Discussion & Questions</h2>
                        <p class="text-xs" style="color:rgb(148,163,184);">Échangez librement sur les créneaux, les détails des cours ou les méthodes pédagogiques.</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold" style="background: rgba(99,102,241,0.15); color: rgb(99,102,241);">
                    {{ $demande->commentaires->count() }} message(s)
                </span>
            </div>

            {{-- Formulaire d'envoi de message --}}
            <form method="POST" action="{{ route('demandes.commentaires.store', $demande) }}" class="space-y-3 mb-6 p-4 rounded-xl" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
                @csrf
                <div class="form-group" style="margin-bottom:0;">
                    <label for="contenu" class="form-label text-xs">Votre message</label>
                    <textarea id="contenu" name="contenu" rows="2" required class="form-textarea text-sm"
                              placeholder="Bonjour, je voulais savoir si vous donnez des cours le weekend ou en distanciel ?"></textarea>
                    @error('contenu')
                        <p class="text-xs mt-1" style="color:rgb(239,68,68);">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Envoyer le message
                    </button>
                </div>
            </form>

            {{-- Liste des commentaires --}}
            @if($demande->commentaires->isEmpty())
                <div class="empty-state py-8">
                    <div class="empty-state-icon">💭</div>
                    <p class="empty-state-title text-sm">Aucun message pour le moment</p>
                    <p class="empty-state-desc text-xs">Posez une question ou engagez la discussion pour convenir des modalités.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($demande->commentaires as $com)
                        <div class="p-4 rounded-xl flex items-start gap-3 transition-colors"
                             style="background: {{ $com->user_id === Auth::id() ? 'rgba(99,102,241,0.08)' : 'rgba(255,255,255,0.03)' }}; border: 1px solid {{ $com->user_id === Auth::id() ? 'rgba(99,102,241,0.25)' : 'rgba(255,255,255,0.05)' }};">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                 style="background: {{ $com->user->hasRole('tuteur') ? 'linear-gradient(135deg, rgb(168,85,247), rgb(236,72,153))' : 'linear-gradient(135deg, rgb(99,102,241), rgb(59,130,246))' }};">
                                {{ strtoupper(substr($com->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-bold text-white text-xs">{{ $com->user->name }}</span>
                                    @if($com->user->hasRole('tuteur'))
                                        <span class="badge-info text-[10px] px-1.5 py-0.5">👨‍🏫 Tuteur</span>
                                    @elseif($com->user->hasRole('admin'))
                                        <span class="badge-purple text-[10px] px-1.5 py-0.5">🛡️ Admin</span>
                                    @else
                                        <span class="badge-success text-[10px] px-1.5 py-0.5">🎓 Apprenant</span>
                                    @endif
                                    <span class="text-[11px]" style="color:rgb(148,163,184);">· {{ $com->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs leading-relaxed" style="color: rgb(226,232,240);">
                                    {{ $com->contenu }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
