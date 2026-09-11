<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                @php
                    $statuts = [
                        'en_attente_moderation' => ['bg-amber-500/10 text-amber-400 border-amber-500/20', 'En attente de modération'],
                        'en_moderation'         => ['bg-amber-500/10 text-amber-400 border-amber-500/20', 'En modération'],
                        'ouverte'               => ['bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'Ouverte aux tuteurs'],
                        'en_cours'              => ['bg-sky-500/10 text-sky-400 border-sky-500/20', 'En cours'],
                        'terminee'              => ['bg-purple-500/10 text-purple-400 border-purple-500/20', 'Terminée'],
                        'refusee'               => ['bg-rose-500/10 text-rose-400 border-rose-500/20', 'Refusée'],
                    ];
                    [$badgeCls, $badgeLabel] = $statuts[$demande->statut] ?? ['bg-slate-800 text-slate-300 border-slate-700', $demande->statut];
                @endphp
                <div class="flex items-center gap-3 flex-wrap mb-1.5">
                    <h1 class="page-title text-2xl font-black text-white font-display">{{ $demande->matiere }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20">{{ $demande->niveau }}</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeCls }}">{{ $badgeLabel }}</span>
                </div>
                <p class="page-subtitle text-xs text-slate-400 mt-0.5">
                    Publiée par <strong class="text-white font-semibold">{{ $demande->apprenant->name }}</strong>
                    · le {{ $demande->created_at->translatedFormat('d F Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
                <a href="{{ route('demandes.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-[#101726] border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white text-xs font-bold transition">
                    &larr; Retour
                </a>
                @can('update', $demande)
                    <a href="{{ route('demandes.edit', $demande) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-amber-500/10 border border-amber-500/25 hover:bg-amber-500/20 text-amber-400 text-xs font-bold transition">
                        Modifier
                    </a>
                @endcan
                @can('delete', $demande)
                    <form method="POST" action="{{ route('demandes.destroy', $demande) }}"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-rose-500/10 border border-rose-500/25 hover:bg-rose-500/20 text-rose-400 text-xs font-bold transition cursor-pointer">
                            Supprimer
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Alerte statut --}}
        @if(in_array($demande->statut, ['en_attente_moderation', 'en_moderation']))
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/25 text-amber-300 text-xs flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 text-amber-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <strong class="font-bold text-amber-200">Demande en cours d'examen</strong>
                    <p class="text-xs text-slate-300 mt-0.5">Votre demande est en cours de validation par notre équipe pédagogique. Dès son approbation, les professeurs qualifiés pourront formuler des propositions.</p>
                </div>
            </div>
        @elseif($demande->statut === 'refusee')
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/25 text-rose-300 text-xs flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 text-rose-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <strong class="font-bold text-rose-200">Demande non validée</strong>
                    <p class="text-xs text-slate-300 mt-0.5"><span class="font-semibold text-rose-300">Motif :</span> {{ $demande->motif_refus ?? 'Non conforme aux critères.' }}</p>
                    @can('update', $demande)
                        <a href="{{ route('demandes.edit', $demande) }}" class="text-xs font-bold underline mt-1.5 inline-block text-rose-300 hover:text-rose-200">Modifier et soumettre à nouveau &rarr;</a>
                    @endcan
                </div>
            </div>
        @elseif($demande->statut === 'en_cours')
            <div class="p-4 rounded-2xl bg-sky-500/10 border border-sky-500/25 text-sky-300 text-xs flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <strong class="font-bold text-sky-200">Séances en cours d'exécution</strong>
                        <p class="text-xs text-slate-300 mt-0.5">Une offre a été validée. Les coordonnées de contact direct sont accessibles ci-dessous.</p>
                    </div>
                </div>
                @if(Auth::id() === $demande->apprenant_id)
                    <form method="POST" action="{{ route('demandes.terminer', $demande) }}" onsubmit="return confirm('Confirmez-vous que les séances de tutorat sont terminées ?');">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-primary text-xs py-2 px-4">
                            <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Marquer comme terminée
                        </button>
                    </form>
                @endif
            </div>
        @elseif($demande->statut === 'terminee')
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 text-xs flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 text-emerald-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <div>
                    <strong class="font-bold text-emerald-200">Accompagnement achevé</strong>
                    <p class="text-xs text-slate-300 mt-0.5">Cette session de tutorat a été clôturée avec succès.</p>
                </div>
            </div>
        @endif

        {{-- Détails de la demande --}}
        <div class="card p-8 rounded-3xl shadow-xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6 border-b border-slate-800">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Matière</span>
                    <span class="text-xl font-black text-white font-display">{{ $demande->matiere }}</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Niveau</span>
                    <span class="text-xl font-black text-sky-400 font-display">{{ $demande->niveau }}</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Budget prévu</span>
                    <span class="text-2xl font-black text-amber-400 font-display">{{ number_format($demande->budget, 0) }} DH</span>
                </div>
            </div>
            <div class="mt-6">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-3">Description du besoin</span>
                <div class="text-sm text-slate-200 leading-relaxed p-6 rounded-2xl bg-[#090D16] border border-slate-800 whitespace-pre-line font-normal">
                    {{ $demande->description }}
                </div>
            </div>
        </div>

        {{-- Proposer une offre (tuteur) --}}
        @if(Auth::user()->hasRole('tuteur') && $demande->statut === 'ouverte' && $demande->apprenant_id !== Auth::id())
            @php $monOffre = $demande->offres->where('tuteur_id', Auth::id())->first(); @endphp
            @if(!$monOffre)
                <div class="card p-8 rounded-3xl shadow-xl border-amber-500/30 bg-gradient-to-br from-[#101726] to-amber-950/20">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-amber-400 bg-amber-500/15 border border-amber-500/30 font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-white font-display">Soumettre votre proposition</h2>
                            <p class="text-xs text-slate-400">Précisez votre approche pédagogique et votre tarif à cet apprenant.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('demandes.offres.store', $demande) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group mb-0">
                                <label for="tarif_propose" class="form-label">Tarif proposé (DH) <span class="text-amber-400">*</span></label>
                                <div class="relative">
                                    <input id="tarif_propose" type="number" step="0.01" min="1" name="tarif_propose"
                                           value="{{ old('tarif_propose', $demande->budget) }}" required
                                           class="form-input pr-12">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-amber-400">DH</span>
                                </div>
                                @error('tarif_propose')
                                    <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="message" class="form-label">Message de présentation <span class="text-amber-400">*</span></label>
                            <textarea id="message" name="message" rows="4" required class="form-textarea"
                                      placeholder="Présentez brièvement vos qualifications, votre méthode et vos disponibilités...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end pt-3">
                            <button type="submit" class="btn-primary text-xs py-2.5 px-6">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Envoyer ma proposition</span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/25 flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-2 text-xs text-amber-300">
                        <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Vous avez déjà soumis une proposition de <strong class="text-white">{{ number_format($monOffre->tarif_propose, 0) }} DH</strong> (Statut : <strong class="text-amber-400">{{ $monOffre->statut }}</strong>).</span>
                    </div>
                    <a href="{{ route('offres.index') }}" class="text-xs font-bold text-amber-400 hover:underline">Voir mes offres &rarr;</a>
                </div>
            @endif
        @endif

        @php
            $offreRetenue = $demande->offres->where('statut', 'acceptee')->first();
        @endphp

        {{-- Section Avis / Évaluation --}}
        @if($offreRetenue)
            @if($demande->avis)
                {{-- Avis déjà déposé --}}
                <div class="card p-6 rounded-3xl border-amber-500/30 bg-[#101726]">
                    <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-amber-400 bg-amber-500/15 border border-amber-500/30 font-bold">
                                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-base font-display">Évaluation de la prestation</h3>
                                <p class="text-xs text-slate-400">
                                    Avis déposé par {{ $demande->apprenant->name }} pour {{ $offreRetenue->tuteur->name }} · {{ $demande->avis->created_at->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 bg-[#090D16] px-3.5 py-1.5 rounded-full border border-slate-800">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $demande->avis->note ? 'text-amber-400' : 'text-slate-700' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="font-black text-amber-400 ml-1.5 text-xs">{{ $demande->avis->note }}/5</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl text-sm leading-relaxed bg-[#090D16] border border-amber-500/20 text-slate-300 italic">
                        "{{ $demande->avis->commentaire }}"
                    </div>
                </div>
            @elseif(Auth::id() === $demande->apprenant_id && in_array($demande->statut, ['en_cours', 'terminee']))
                {{-- Formulaire de notation 1-5 + commentaire --}}
                <div class="card p-6 rounded-3xl border-amber-500/30 bg-[#101726]" x-data="{ rating: 5, hoverRating: 0 }">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-amber-400 bg-amber-500/15 border border-amber-500/30 font-bold">
                            <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-base font-display">Évaluer le tuteur ({{ $offreRetenue->tuteur->name }})</h3>
                            <p class="text-xs text-slate-400">Partagez votre avis pour valoriser la qualité pédagogique du professeur.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('avis.store', $demande) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="note" :value="rating">

                        <div class="form-group mb-0">
                            <label class="form-label">Votre appréciation globale</label>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                        <button type="button"
                                                @click="rating = star"
                                                @mouseenter="hoverRating = star"
                                                @mouseleave="hoverRating = 0"
                                                class="p-1 focus:outline-none transition-transform hover:scale-110 cursor-pointer">
                                            <svg class="w-8 h-8 transition-colors duration-150"
                                                 :class="(hoverRating ? hoverRating >= star : rating >= star) ? 'text-amber-400' : 'text-slate-700'"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                                <span class="text-sm font-bold text-amber-400" x-text="(hoverRating || rating) + ' / 5 étoiles'"></span>
                            </div>
                            @error('note')
                                <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label for="commentaire" class="form-label">Votre commentaire d'évaluation</label>
                            <textarea id="commentaire" name="commentaire" rows="3" required class="form-textarea"
                                      placeholder="Pédagogie, ponctualité, clarté des explications... Partagez votre retour d'expérience.">{{ old('commentaire') }}</textarea>
                            @error('commentaire')
                                <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="btn-primary text-xs py-2.5 px-6">
                                Valider mon évaluation
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        @endif

        {{-- Liste des offres reçues --}}
        <div class="card p-8 rounded-3xl shadow-xl">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                <h2 class="text-lg font-black text-white font-display flex items-center gap-2">
                    Propositions reçues
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">{{ $demande->offres->count() }}</span>
                </h2>
            </div>

            @if($demande->offres->isEmpty())
                <div class="empty-state py-12 text-center">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-[#090D16] border border-slate-800 flex items-center justify-center text-slate-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-white">Aucune proposition reçue pour le moment</p>
                    @if($demande->statut === 'ouverte' && Auth::user()->hasRole('tuteur'))
                        <p class="text-xs font-semibold text-amber-400 mt-1">Soyez le premier professeur à postuler.</p>
                    @else
                        <p class="text-xs text-slate-400 mt-1">Les propositions des professeurs certifiés apparaîtront ici.</p>
                    @endif
                </div>
            @else
                <div class="space-y-5">
                    @foreach($demande->offres as $offre)
                        <div class="rounded-2xl p-6 transition-all duration-200 border {{ $offre->statut === 'acceptee' ? 'border-emerald-500/30 bg-emerald-500/[0.05]' : 'border-slate-800 bg-[#090D16]' }}">

                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div class="flex-1 space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xs font-black text-white flex-shrink-0 shadow-sm bg-gradient-to-br from-amber-500 to-orange-600">
                                            {{ strtoupper(substr($offre->tuteur->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="font-bold text-white text-base">{{ $offre->tuteur->name }}</h3>
                                                @if($offre->tuteur->note_moyenne)
                                                    <span class="text-xs font-bold text-amber-400 flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                        {{ $offre->tuteur->note_moyenne }}/5 ({{ $offre->tuteur->avisRecus->count() }} avis)
                                                    </span>
                                                @endif
                                                @if($offre->statut === 'acceptee')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Offre acceptée</span>
                                                @elseif($offre->statut === 'refusee')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">Non retenue</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">En attente</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-3 flex-wrap text-xs text-slate-400 mt-0.5">
                                                @if($offre->tuteur->matiere)
                                                    <span>Spécialité : <strong class="text-slate-200">{{ $offre->tuteur->matiere }}</strong></span>
                                                @endif
                                                @if($offre->tuteur->tarif_horaire)
                                                    <span>Tarif usuel : <strong class="text-amber-400">{{ number_format($offre->tuteur->tarif_horaire, 0) }} DH/h</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($offre->tuteur->bio)
                                        <div class="p-3.5 rounded-xl text-xs leading-relaxed bg-[#101726] border border-slate-800 text-slate-300">
                                            <strong class="text-amber-300">À propos :</strong> {{ $offre->tuteur->bio }}
                                        </div>
                                    @endif

                                    <div class="text-sm leading-relaxed p-4 rounded-xl bg-[#101726] border border-slate-800 text-slate-200 whitespace-pre-line">
                                        {{ $offre->message }}
                                    </div>

                                    @if($offre->statut === 'acceptee' || $offre->coordonnees_visibles)
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
                                        <div class="p-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 space-y-4">
                                            <p class="text-xs font-black text-emerald-400 flex items-center gap-1.5 uppercase tracking-wider">
                                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                Coordonnées de contact débloquées
                                            </p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div class="p-4 rounded-xl bg-[#090D16] border border-emerald-500/20 space-y-2.5">
                                                    <p class="text-xs font-bold text-white">Tuteur : {{ $offre->tuteur->name }}</p>
                                                    <p class="text-xs text-slate-400"><span class="font-medium">Email :</span> <a href="mailto:{{ $offre->tuteur->email }}" class="text-amber-400 hover:underline">{{ $offre->tuteur->email }}</a></p>
                                                    @if($offre->tuteur->telephone)
                                                        <div class="flex items-center gap-2 pt-1 flex-wrap">
                                                            <a href="tel:{{ $offre->tuteur->telephone }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#101726] border border-slate-700 hover:border-slate-600 text-xs text-slate-200">
                                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                                Appeler
                                                            </a>
                                                            @if($cleanTelTuteur)
                                                                <a href="https://wa.me/{{ $cleanTelTuteur }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#25D366] hover:bg-[#20ba59] text-xs font-bold text-white shadow-sm transition">
                                                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.586-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086.159.058 1.011.477 1.184.564.173.086.289.13.332.202.043.072.043.419-.101.824z"/></svg>
                                                                    WhatsApp
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="p-4 rounded-xl bg-[#090D16] border border-emerald-500/20 space-y-2.5">
                                                    <p class="text-xs font-bold text-white">Apprenant : {{ $demande->apprenant->name }}</p>
                                                    <p class="text-xs text-slate-400"><span class="font-medium">Email :</span> <a href="mailto:{{ $demande->apprenant->email }}" class="text-amber-400 hover:underline">{{ $demande->apprenant->email }}</a></p>
                                                    @if($demande->apprenant->telephone)
                                                        <div class="flex items-center gap-2 pt-1 flex-wrap">
                                                            <a href="tel:{{ $demande->apprenant->telephone }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#101726] border border-slate-700 hover:border-slate-600 text-xs text-slate-200">
                                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                                Appeler
                                                            </a>
                                                            @if($cleanTelApprenant)
                                                                <a href="https://wa.me/{{ $cleanTelApprenant }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#25D366] hover:bg-[#20ba59] text-xs font-bold text-white shadow-sm transition">
                                                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.586-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086.159.058 1.011.477 1.184.564.173.086.289.13.332.202.043.072.043.419-.101.824z"/></svg>
                                                                    WhatsApp
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col sm:items-end justify-between gap-3 flex-shrink-0 pt-2 sm:pt-0">
                                    <div class="sm:text-right">
                                        <span class="text-[11px] font-bold text-slate-400 block uppercase tracking-wider">Tarif proposé</span>
                                        <span class="text-2xl font-black text-amber-400 font-display">{{ number_format($offre->tarif_propose, 0) }} DH</span>
                                    </div>
                                    @if($offre->statut === 'acceptee')
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            Offre retenue
                                        </span>
                                    @elseif(Auth::id() === $demande->apprenant_id && $demande->statut === 'ouverte' && $offre->statut === 'en_attente')
                                        <form method="POST" action="{{ route('offres.accepter', $offre) }}"
                                              onsubmit="return confirm('Accepter cette offre ? Les coordonnées de contact mutuelles seront immédiatement débloquées.');">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white text-xs font-bold shadow-lg shadow-emerald-500/20 transition cursor-pointer">
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

        {{-- Section Discussion & Commentaires --}}
        <div class="card p-8 rounded-3xl shadow-xl" id="espace-commentaires">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-amber-400 bg-amber-500/10 border border-amber-500/20 font-bold">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-white font-display">Questions & Échanges</h2>
                        <p class="text-xs text-slate-400">Posez vos questions sur les créneaux, les modalités ou le programme.</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    {{ $demande->commentaires->count() }} message(s)
                </span>
            </div>

            <form method="POST" action="{{ route('demandes.commentaires.store', $demande) }}" class="space-y-3 mb-6 p-4 rounded-2xl bg-[#090D16] border border-slate-800">
                @csrf
                <div class="form-group mb-0">
                    <label for="contenu" class="form-label text-xs">Votre question ou précision</label>
                    <textarea id="contenu" name="contenu" rows="2" required class="form-textarea text-sm"
                              placeholder="Posez votre question sur les disponibilités ou le format des séances..."></textarea>
                    @error('contenu')
                        <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end pt-1">
                    <button type="submit" class="btn-primary text-xs py-2 px-5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Envoyer le message
                    </button>
                </div>
            </form>

            @if($demande->commentaires->isEmpty())
                <div class="empty-state py-8 text-center">
                    <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-[#090D16] border border-slate-800 flex items-center justify-center text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-white">Aucun message pour le moment</p>
                    <p class="text-xs text-slate-400">Engagez la discussion pour échanger avec le professeur ou l'élève.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($demande->commentaires as $com)
                        <div class="p-4 rounded-2xl flex items-start gap-3 border {{ $com->user_id === Auth::id() ? 'bg-amber-500/[0.06] border-amber-500/25' : 'bg-[#090D16] border-slate-800' }}">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black text-white flex-shrink-0"
                                 style="background: {{ $com->user->hasRole('tuteur') ? 'linear-gradient(135deg, #f59e0b, #ea580c)' : ($com->user->hasRole('admin') ? 'linear-gradient(135deg, #a855f7, #6366f1)' : 'linear-gradient(135deg, #0284c7, #0ea5e9)') }};">
                                {{ strtoupper(substr($com->user->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-bold text-white text-xs">{{ $com->user->name }}</span>
                                    @if($com->user->hasRole('tuteur'))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Tuteur</span>
                                    @elseif($com->user->hasRole('admin'))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">Admin</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20">Apprenant</span>
                                    @endif
                                    <span class="text-[11px] text-slate-400">· {{ $com->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs leading-relaxed text-slate-200 whitespace-pre-line">
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