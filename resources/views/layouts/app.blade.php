<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TutorLink') }} — {{ $title ?? 'Tableau de bord' }}</title>
    <meta name="description" content="TutorLink — La plateforme qui connecte apprenants et tuteurs freelance">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" style="background: rgb(15,15,25); color: #f8fafc;">

<div class="app-shell" x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display:none;"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" :class="{ 'open': sidebarOpen }">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">TL</div>
            <span class="sidebar-logo-text">TutorLink</span>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <p class="sidebar-section-label mt-2 mb-3">Menu principal</p>

            <a href="{{ route('dashboard') }}"
               class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('demandes.index') }}"
               class="sidebar-item {{ request()->routeIs('demandes.*') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ Auth::user()->hasRole('apprenant') ? 'Mes demandes' : 'Demandes de cours' }}
            </a>

            @if(Auth::user()->hasRole('apprenant'))
                <a href="{{ route('demandes.create') }}"
                   class="sidebar-item {{ request()->routeIs('demandes.create') ? 'active' : '' }}">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle demande
                </a>
            @endif

            @if(Auth::user()->hasRole('tuteur') || Auth::user()->hasRole('admin'))
                <a href="{{ route('offres.index') }}"
                   class="sidebar-item {{ request()->routeIs('offres.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Mes offres
                </a>
            @endif

            @if(Auth::user()->hasRole('admin'))
                <div class="divider" style="margin: 12px 0;"></div>
                <p class="sidebar-section-label mb-3">Administration</p>

                <a href="{{ route('admin.moderation.index') }}"
                   class="sidebar-item {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}"
                   style="{{ request()->routeIs('admin.moderation.*') ? '' : 'color: #fbbf24;' }}">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Modération
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                   style="{{ request()->routeIs('admin.users.*') ? '' : 'color: #38bdf8;' }}">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Utilisateurs
                </a>
            @endif
        </nav>

        <!-- User Footer -->
        <div class="sidebar-footer" x-data="{ dropOpen: false }">
            <div class="sidebar-user" @click="dropOpen = !dropOpen">
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs truncate" style="color: rgb(148, 163, 184);">
                        @if(Auth::user()->hasRole('admin')) 🛡️ Admin
                        @elseif(Auth::user()->hasRole('tuteur')) 👨‍🏫 Tuteur
                        @else 🎓 Apprenant @endif
                    </p>
                </div>
                <svg class="w-4 h-4 flex-shrink-0" style="color: rgb(148,163,184);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div x-show="dropOpen" x-transition style="display:none;" class="mt-2 rounded-xl overflow-hidden" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-white/5 transition-colors" style="color: rgb(148,163,184); text-decoration:none;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Mon profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 text-sm w-full text-left hover:bg-white/5 transition-colors" style="color: rgb(239,68,68); background:none; border:none; cursor:pointer;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Top Bar (mobile) -->
        <div class="flex items-center gap-4 px-6 py-4 lg:hidden" style="background: rgb(22,22,38); border-bottom: 1px solid rgba(255,255,255,0.06);">
            <button @click="sidebarOpen = true" class="p-2 rounded-lg hover:bg-white/10 transition-colors" style="color: rgb(148,163,184);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-bold text-white">TutorLink</span>
        </div>

        <!-- Page Header -->
        @isset($header)
        <div class="page-header">
            {{ $header }}
        </div>
        @endisset

        <!-- Flash Messages -->
        <div class="px-8 pt-6">
            @if(session('success'))
                <div class="alert-success animate-slide-up">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-danger animate-slide-up">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
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
