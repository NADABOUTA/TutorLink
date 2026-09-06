<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">Gestion des Utilisateurs</h1>
                <p class="page-subtitle">Consultez la liste des membres, vérifiez leurs rôles et gérez leurs accès.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.moderation.index') }}" class="btn-secondary btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Panneau de modération
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Statistiques --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="stat-card indigo">
                <div class="stat-icon" style="background: rgba(99,102,241,0.15);">
                    <svg class="w-5 h-5" style="color:rgb(99,102,241);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total utilisateurs</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon" style="background: rgba(168,85,247,0.15);">
                    <svg class="w-5 h-5" style="color:rgb(168,85,247);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div class="stat-number">{{ $stats['apprenants'] }}</div>
                <div class="stat-label">Apprenants inscrits</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon" style="background: rgba(34,197,94,0.15);">
                    <svg class="w-5 h-5" style="color:rgb(34,197,94);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div class="stat-number">{{ $stats['tuteurs'] }}</div>
                <div class="stat-label">Tuteurs freelance</div>
            </div>
            <div class="stat-card" style="background: rgba(239,68,68,0.06); border-color: rgba(239,68,68,0.2);">
                <div class="stat-icon" style="background: rgba(239,68,68,0.15);">
                    <svg class="w-5 h-5" style="color:rgb(239,68,68);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <div class="stat-number" style="color:rgb(239,68,68);">{{ $stats['inactifs'] }}</div>
                <div class="stat-label">Comptes désactivés</div>
            </div>
        </div>

        {{-- Filtres de recherche --}}
        <div class="card">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <div class="form-group sm:col-span-2" style="margin-bottom:0;">
                    <label for="search" class="form-label">Rechercher</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="form-input" placeholder="Nom, adresse email ou matière...">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="role" class="form-label">Rôle</label>
                    <select name="role" id="role" class="form-select">
                        <option value="">Tous les rôles</option>
                        <option value="apprenant" {{ request('role') === 'apprenant' ? 'selected' : '' }}>Apprenant</option>
                        <option value="tuteur" {{ request('role') === 'tuteur' ? 'selected' : '' }}>Tuteur</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="statut" class="form-label">Statut</label>
                    <select name="statut" id="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Désactivé</option>
                    </select>
                </div>
                <div class="sm:col-span-3 lg:col-span-4 flex items-center justify-end gap-3 pt-2">
                    @if(request()->hasAny(['search', 'role', 'statut']))
                        <a href="{{ route('admin.users.index') }}" class="btn-secondary btn-sm">Réinitialiser</a>
                    @endif
                    <button type="submit" class="btn-primary btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Appliquer les filtres
                    </button>
                </div>
            </form>
        </div>

        {{-- Table des Utilisateurs --}}
        <div class="card overflow-hidden p-0" style="padding:0;">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Coordonnées</th>
                            <th>Rôles</th>
                            <th>Note moyenne</th>
                            <th>Statut</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
                                             style="background: linear-gradient(135deg, rgb(99,102,241), rgb(168,85,247));">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white flex items-center gap-1.5">
                                                {{ $user->name }}
                                                @if($user->id === Auth::id())
                                                    <span class="text-xs font-normal" style="color:rgb(148,163,184);">(Vous)</span>
                                                @endif
                                            </div>
                                            <p class="text-xs" style="color:rgb(148,163,184);">
                                                Inscrit le {{ $user->created_at->translatedFormat('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-xs space-y-1">
                                        <p class="text-white">{{ $user->email }}</p>
                                        @if($user->telephone)
                                            <p style="color:rgb(148,163,184);">📱 {{ $user->telephone }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse($user->roles as $role)
                                            @if($role->name === 'admin')
                                                <span class="badge-purple">Admin</span>
                                            @elseif($role->name === 'tuteur')
                                                <span class="badge-info">Tuteur</span>
                                            @else
                                                <span class="badge-success">Apprenant</span>
                                            @endif
                                        @empty
                                            <span class="badge-pending">Sans rôle</span>
                                        @endforelse
                                    </div>
                                    @if($user->matiere)
                                        <p class="text-xs mt-1" style="color:rgb(148,163,184);">Matière : {{ $user->matiere }}</p>
                                    @endif
                                </td>
                                <td>
                                    @if($user->hasRole('tuteur'))
                                        @if($user->note_moyenne)
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-white text-sm">★ {{ $user->note_moyenne }}</span>
                                                <span class="text-xs" style="color:rgb(148,163,184);">({{ $user->avisRecus->count() }} avis)</span>
                                            </div>
                                        @else
                                            <span class="text-xs" style="color:rgb(148,163,184);">Aucun avis</span>
                                        @endif
                                    @else
                                        <span class="text-xs" style="color:rgb(100,116,139);">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge-success">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1" style="background: rgb(34,197,94);"></span>
                                            Actif
                                        </span>
                                    @else
                                        <span class="badge-danger">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1" style="background: rgb(239,68,68);"></span>
                                            Désactivé
                                        </span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir {{ $user->is_active ? 'désactiver' : 'activer' }} le compte de {{ addslashes($user->name) }} ?');"
                                              class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->is_active)
                                                <button type="submit" class="btn-danger btn-sm" title="Désactiver ce compte">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    Désactiver
                                                </button>
                                            @else
                                                <button type="submit" class="btn-success btn-sm" title="Réactiver ce compte">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Activer
                                                </button>
                                            @endif
                                        </form>
                                    @else
                                        <span class="text-xs italic" style="color:rgb(100,116,139);">Votre compte</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">👥</div>
                                        <p class="empty-state-title">Aucun utilisateur trouvé</p>
                                        <p class="empty-state-desc">Essayez de modifier vos critères de recherche.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
