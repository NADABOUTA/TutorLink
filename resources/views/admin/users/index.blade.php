<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="page-title text-2xl font-black text-white font-display">Gestion des Utilisateurs</h1>
                <p class="page-subtitle text-xs text-slate-400 mt-0.5">Consultez la liste des membres, vérifiez leurs rôles et gérez leurs accès.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.moderation.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#101726] border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white text-xs font-bold transition shadow-sm">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Panneau de modération</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- 4 Stat Cards (Screenshot 5) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total utilisateurs</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div class="stat-number">{{ $stats['apprenants'] }}</div>
                <div class="stat-label">Apprenants inscrits</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div class="stat-number">{{ $stats['tuteurs'] }}</div>
                <div class="stat-label">Tuteurs certifiés</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <div class="stat-number text-rose-400">{{ $stats['inactifs'] }}</div>
                <div class="stat-label">Comptes suspendus</div>
            </div>
        </div>

        {{-- Filters (Screenshot 5) --}}
        <div class="card p-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="form-group sm:col-span-2 mb-0">
                        <label for="search" class="form-label">Rechercher un membre</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="form-input" placeholder="Nom, adresse email ou matière...">
                    </div>
                    <div class="form-group mb-0">
                        <label for="role" class="form-label">Rôle</label>
                        <select name="role" id="role" class="form-select">
                            <option value="">Tous les rôles</option>
                            <option value="apprenant" {{ request('role') === 'apprenant' ? 'selected' : '' }}>Apprenant</option>
                            <option value="tuteur" {{ request('role') === 'tuteur' ? 'selected' : '' }}>Tuteur</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label for="statut" class="form-label">Statut du compte</label>
                        <select name="statut" id="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Désactivé</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    @if(request()->hasAny(['search', 'role', 'statut']))
                        <a href="{{ route('admin.users.index') }}" class="btn-secondary text-xs py-2.5">Réinitialiser</a>
                    @endif
                    <button type="submit" class="btn-primary text-xs py-2.5 px-5">
                        <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Filtrer les résultats
                    </button>
                </div>
            </form>
        </div>

        {{-- Users Table (Screenshot 5) --}}
        <div class="table-wrapper">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Coordonnées</th>
                            <th>Rôle</th>
                            <th>Évaluation</th>
                            <th>Statut</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="transition-colors hover:bg-white/[0.02]">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0 shadow-md bg-blue-600">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white flex items-center gap-1.5 text-sm">
                                                {{ $user->name }}
                                                @if($user->id === Auth::id())
                                                    <span class="text-xs text-slate-400 font-normal">(Vous)</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-400 mt-0.5">
                                                Inscrit le {{ $user->created_at->translatedFormat('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-xs space-y-0.5">
                                        <p class="font-semibold text-slate-200">{{ $user->email }}</p>
                                        @if($user->telephone)
                                            <p class="text-slate-400">Tél : {{ $user->telephone }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-1">
                                        <div>
                                            @if($user->isAdmin())
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/30">Admin</span>
                                            @elseif($user->isTuteur())
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">Tuteur</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-500/10 text-sky-400 border border-sky-500/30">Apprenant</span>
                                            @endif
                                        </div>
                                        @if($user->matiere)
                                            <p class="text-[11px] text-slate-400">Spécialité : {{ $user->matiere }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($user->hasRole('tuteur'))
                                        @if($user->note_moyenne)
                                            <div class="flex items-center gap-1">
                                                <span class="text-amber-400 text-xs">★</span>
                                                <span class="font-bold text-white text-sm">{{ $user->note_moyenne }}</span>
                                                <span class="text-xs text-slate-400">({{ $user->avisRecus->count() }})</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400">Aucun avis</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-500">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            Actif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-rose-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                            Suspendu
                                        </span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}"
                                              onsubmit="return confirm('Confirmez-vous le changement de statut pour {{ addslashes($user->name) }} ?');"
                                              class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->is_active)
                                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold text-rose-400 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500/20 transition cursor-pointer" title="Suspendre ce compte">
                                                    Désactiver
                                                </button>
                                            @else
                                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 hover:bg-emerald-500/20 transition cursor-pointer" title="Réactiver ce compte">
                                                    Activer
                                                </button>
                                            @endif
                                        </form>
                                    @else
                                        <span class="text-xs italic text-slate-500">Votre compte</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="empty-state">
                                        <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-800/60 flex items-center justify-center text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        </div>
                                        <p class="empty-state-title text-sm font-bold text-white">Aucun utilisateur trouvé</p>
                                        <p class="empty-state-desc text-xs text-slate-400 mt-1">Essayez de modifier vos critères de recherche.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-4 border-t border-slate-800">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>