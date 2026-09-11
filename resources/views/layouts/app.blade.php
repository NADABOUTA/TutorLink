<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TutorLink') }} — {{ $title ?? 'Plateforme de Soutien Scolaire' }}</title>
    <meta name="description" content="TutorLink — La plateforme de référence connectant apprenants et tuteurs particuliers au Maroc">

    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#fafaf9] text-[#121212] selection:bg-neutral-900 selection:text-white">

<div class="app-shell" x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display:none;"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" :class="{ 'open': sidebarOpen }">
        <!-- Brand Logo -->
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">TL</div>
            <div class="flex flex-col">
                <span class="sidebar-logo-text">Tutor<span>Link</span></span>
                <span class="text-[9px] font-bold tracking-widest text-neutral-400 uppercase -mt-0.5">Maroc · Studio</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <p class="sidebar-section-label">Navigation</p>

            {{-- Tableau de bord (Tous les utilisateurs) --}}
            <a href="{{ route('dashboard') }}"
               class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Tableau de bord</span>
            </a>

            @if(Auth::user()->isAdmin())
                {{-- ESPACE STRICTEMENT ADMINISTRATEUR --}}
                <a href="{{ route('admin.moderation.index') }}"
                   class="sidebar-item {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Modération</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Utilisateurs</span>
                </a>
            @else
                {{-- ESPACE APPRENANT ET TUTEUR --}}
                <a href="{{ route('demandes.index') }}"
                   class="sidebar-item {{ request()->routeIs('demandes.index') || request()->routeIs('demandes.show') ? 'active' : '' }}">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>{{ Auth::user()->isApprenant() ? 'Mes demandes' : 'Demandes de cours' }}</span>
                </a>

                @if(Auth::user()->isApprenant())
                    <a href="{{ route('demandes.create') }}"
                       class="sidebar-item {{ request()->routeIs('demandes.create') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Nouvelle demande</span>
                    </a>
                @endif

                @if(Auth::user()->isTuteur())
                    <a href="{{ route('offres.index') }}"
                       class="sidebar-item {{ request()->routeIs('offres.*') ? 'active' : '' }}">
                        <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span>Mes candidatures</span>
                    </a>
                @endif
            @endif

            {{-- Notifications (Pour tous les profils y compris l'admin) --}}
            <a href="{{ route('notifications.index') }}"
               class="sidebar-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="flex-1">Notifications</span>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-600 text-white shadow-xs">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
        </nav>


        <!-- User Footer -->
        <div class="sidebar-footer" x-data="{ dropOpen: false }">
            <div class="sidebar-user" @click="dropOpen = !dropOpen">
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 font-medium capitalize">
                        @if(Auth::user()->isAdmin()) Administrateur
                        @elseif(Auth::user()->isTuteur()) Tuteur Certifié
                        @else Apprenant @endif
                    </p>
                </div>
                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div x-show="dropOpen" @click.away="dropOpen = false" x-transition style="display:none;" class="mt-2 rounded-xl bg-white border border-slate-200 shadow-lg overflow-hidden">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors font-medium">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Mon profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2.5 px-4 py-2.5 text-sm w-full text-left text-rose-600 hover:bg-rose-50 transition-colors font-medium cursor-pointer border-t border-slate-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="main-content">
        <!-- TOP NAVBAR -->
        <header class="page-header">
            
            <!-- Left: Mobile menu button & breadcrumbs -->
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="p-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <span class="text-slate-900 font-bold lg:hidden">TutorLink</span>
                    <span class="hidden lg:inline text-slate-400">TutorLink</span>
                    <span class="hidden lg:inline text-slate-300">/</span>
                    <span class="text-slate-700 font-semibold">{{ $title ?? 'Espace membre' }}</span>
                </div>
            </div>

            <!-- Right: Quick Access & Profile -->
            <div class="flex items-center gap-3">
                {{-- User mini avatar --}}
                <a href="{{ route('profile.edit') }}" class="sidebar-avatar hover:opacity-90 transition-opacity"
                   title="{{ Auth::user()->name }}">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </a>
            </div>
        </header>

        <!-- Optional Header slot -->
        @isset($header)
        <div class="page-header border-t-0">
            {{ $header }}
        </div>
        @endisset

        <!-- Flash Messages -->
        <div class="px-8 pt-6 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="alert-success">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-danger">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="page-body">
            {{ $slot }}
        </main>
    </div>

</div>
</body>
</html>
