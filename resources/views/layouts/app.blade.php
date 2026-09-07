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

            <a href="{{ route('notifications.index') }}"
               class="sidebar-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="flex-1">Notifications</span>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>

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
        <!-- TOP NAVBAR (Desktop & Mobile avec Cloche Notifications) -->
        <header class="flex items-center justify-between px-6 py-3.5 sticky top-0 z-30"
                style="background: rgba(15,15,25,0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.06);">
            
            <!-- Left: Mobile menu button & breadcrumbs -->
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="p-2 rounded-xl hover:bg-white/10 transition-colors lg:hidden" style="color: rgb(148,163,184);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-white text-base lg:hidden">TutorLink</span>
                    <div class="hidden lg:flex items-center gap-2 text-xs" style="color: rgb(148,163,184);">
                        <span>TutorLink</span>
                        <span>/</span>
                        <span class="text-white font-medium">{{ $title ?? 'Espace membre' }}</span>
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Notification Bell & Profile -->
            <div class="flex items-center gap-3" x-data="{ notifOpen: false }">
                
                {{-- CLOCHE DE NOTIFICATIONS --}}
                <div class="relative">
                    @php
                        $unreadNotifCount = Auth::user()->unreadNotifications->count();
                        $recentNotifs = Auth::user()->notifications()->take(5)->get();
                    @endphp

                    <button @click="notifOpen = !notifOpen"
                            class="relative p-2.5 rounded-xl transition-all duration-200 hover:bg-white/10 focus:outline-none flex items-center justify-center cursor-pointer"
                            :class="{ 'bg-white/10 text-white': notifOpen, 'text-slate-300': !notifOpen }"
                            style="border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.03);"
                            title="Notifications">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>

                        {{-- Badge non lu --}}
                        @if($unreadNotifCount > 0)
                            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full text-[10px] font-black flex items-center justify-center text-white bg-rose-500 shadow-lg shadow-rose-500/50 animate-pulse">
                                {{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}
                            </span>
                        @endif
                    </button>

                    {{-- DROPDOWN MENU --}}
                    <div x-show="notifOpen"
                         @click.away="notifOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                         class="absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl shadow-2xl overflow-hidden z-50"
                         style="display: none; background: rgb(22,22,38); border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
                        
                        {{-- Header Dropdown --}}
                        <div class="flex items-center justify-between px-4 py-3.5" style="border-bottom: 1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.02);">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-white">Notifications</span>
                                @if($unreadNotifCount > 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">
                                        {{ $unreadNotifCount }} nouvelle(s)
                                    </span>
                                @endif
                            </div>
                            @if($unreadNotifCount > 0)
                                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                    @csrf
                                    <button type="submit" class="text-[11px] font-semibold text-indigo-400 hover:text-indigo-300 hover:underline cursor-pointer bg-transparent border-none">
                                        Tout marquer lu
                                    </button>
                                </form>
                            @endif
                        </div>

                        {{-- Liste des 5 dernières notifications --}}
                        <div class="max-h-80 overflow-y-auto divide-y divide-white/5">
                            @forelse($recentNotifs as $notifItem)
                                @php
                                    $nData = $notifItem->data;
                                    $isItemUnread = is_null($notifItem->read_at);
                                @endphp
                                <form method="POST" action="{{ route('notifications.read', $notifItem->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-left p-3.5 flex items-start gap-3 hover:bg-white/5 transition-colors cursor-pointer {{ $isItemUnread ? 'bg-indigo-500/[0.08]' : '' }}">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm flex-shrink-0 mt-0.5"
                                             style="background: {{ $isItemUnread ? 'rgba(99,102,241,0.25)' : 'rgba(255,255,255,0.05)' }};">
                                            {{ $nData['icon'] ?? '🔔' }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                                <p class="text-xs font-bold text-white truncate">{{ $nData['title'] ?? 'Notification' }}</p>
                                                <span class="text-[10px]" style="color: rgb(148,163,184);">{{ $notifItem->created_at->diffForHumans(null, true, true) }}</span>
                                            </div>
                                            <p class="text-xs line-clamp-2 leading-relaxed" style="color: rgb(203,213,225);">{{ $nData['message'] ?? '' }}</p>
                                        </div>
                                        @if($isItemUnread)
                                            <span class="w-2 h-2 rounded-full bg-indigo-400 mt-2 flex-shrink-0"></span>
                                        @endif
                                    </button>
                                </form>
                            @empty
                                <div class="py-8 text-center px-4">
                                    <span class="text-2xl block mb-2">🔔</span>
                                    <p class="text-xs font-semibold text-white">Aucune notification pour le moment</p>
                                    <p class="text-[11px] mt-1" style="color: rgb(148,163,184);">Vous serez notifié dès qu'un tuteur ou apprenant interagit avec vous.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Footer Dropdown --}}
                        <div class="p-2.5 text-center" style="border-top: 1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.02);">
                            <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 hover:underline inline-flex items-center gap-1.5" style="text-decoration:none;">
                                <span>Voir toutes les notifications</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- User mini avatar shortcut --}}
                <a href="{{ route('profile.edit') }}" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white transition-opacity hover:opacity-80"
                   style="background: linear-gradient(135deg, rgb(99,102,241), rgb(168,85,247)); text-decoration:none;"
                   title="{{ Auth::user()->name }}">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </a>
            </div>
        </header>

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
