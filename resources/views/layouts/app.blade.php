<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TutorLink') }} — {{ $title ?? 'Plateforme de Soutien Scolaire' }}</title>
    <meta name="description" content="TutorLink — La plateforme de référence connectant apprenants et tuteurs particuliers au Maroc">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#0F172A] text-white selection:bg-primary-500/15 selection:text-primary-200">

<div class="app-shell" x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display:none;"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" :class="{ 'open': sidebarOpen }">
        <!-- Brand -->
        <div class="sidebar-logo">
            <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-[#090D16] border border-amber-500/30 shadow-glow-sm">
                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-lg font-black tracking-tight font-display text-white">Tutor<span class="text-amber-500">Link</span></span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <p class="sidebar-section-label">Menu</p>

            {{-- Tableau de bord --}}
            <a href="{{ route('dashboard') }}"
               class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="flex-1">Tableau de bord</span>
            </a>

            @if(Auth::user()->isAdmin())
                @php
                    $pendingModCount = \App\Models\Demande::where('statut', 'en_attente_moderation')->count();
                @endphp
                <a href="{{ route('admin.moderation.index') }}"
                   class="sidebar-item {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="flex-1">Modération</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $pendingModCount > 0 ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-slate-800 text-slate-400' }}">
                        {{ $pendingModCount }}
                    </span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Utilisateurs</span>
                </a>
            @else
                <a href="{{ route('demandes.index') }}"
                   class="sidebar-item {{ request()->routeIs('demandes.index') || request()->routeIs('demandes.show') ? 'active' : '' }}">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>{{ Auth::user()->isApprenant() ? 'Mes demandes' : 'Demandes de cours' }}</span>
                </a>

                @if(Auth::user()->isApprenant())
                    <a href="{{ route('demandes.create') }}"
                       class="sidebar-item {{ request()->routeIs('demandes.create') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Nouvelle demande</span>
                    </a>
                @endif

                @if(Auth::user()->isTuteur())
                    <a href="{{ route('offres.index') }}"
                       class="sidebar-item {{ request()->routeIs('offres.*') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span>Mes candidatures</span>
                    </a>
                @endif
            @endif

            <a href="{{ route('notifications.index') }}"
               class="sidebar-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="flex-1">Notifications</span>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="w-5 h-5 rounded-full text-xs font-black bg-amber-500 text-slate-950 flex items-center justify-center shadow-glow-sm">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
        </nav>

        <!-- User Footer -->
        <div class="sidebar-footer" x-data="{ dropOpen: false }">
            <div class="sidebar-user" @click="dropOpen = !dropOpen">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-slate-950 font-black text-sm shadow-md flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-slate-400 capitalize">
                        @if(Auth::user()->isAdmin()) Administrateur
                        @elseif(Auth::user()->isTuteur()) Tuteur
                        @else Apprenant @endif
                    </p>
                </div>
                <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200" :class="{ 'rotate-180': dropOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div x-show="dropOpen" @click.away="dropOpen = false" x-transition style="display:none;" class="mt-2 rounded-2xl bg-[#101726] border border-slate-800 shadow-xl overflow-hidden">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:bg-white/[0.04] transition-colors font-medium">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Mon profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2.5 px-4 py-2.5 text-sm w-full text-left text-red-400 hover:bg-red-500/10 transition-colors font-medium cursor-pointer border-t border-slate-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <header class="page-header">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="p-2 rounded-xl border border-slate-800 text-slate-400 hover:bg-white/[0.04] transition-colors lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex items-center gap-2 text-xs font-semibold">
                    <span class="text-slate-500">/</span>
                    <span class="text-slate-300 font-medium">{{ $title ?? 'Espace membre' }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Notifications Bell -->
                <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-xl border border-slate-800 bg-[#0B101B] hover:border-slate-700 text-slate-300 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-amber-400 ring-2 ring-[#090D16]"></span>
                    @endif
                </a>

                <!-- User Initial Avatar -->
                <a href="{{ route('profile.edit') }}" class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-slate-950 font-black text-xs shadow-md hover:opacity-90 transition" title="{{ Auth::user()->name }}">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </a>
            </div>
        </header>

        @isset($header)
        <div class="page-header border-t-0">
            {{ $header }}
        </div>
        @endisset

        <div class="px-6 lg:px-8 pt-5 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="alert-success">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-danger">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <main class="page-body">
            {{ $slot }}
        </main>
    </div>

</div>
</body>
</html>
