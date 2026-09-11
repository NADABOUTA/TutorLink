<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TutorLink') }} — {{ $title ?? '' }}</title>
    <meta name="description" content="TutorLink — La plateforme qui connecte apprenants et tuteurs freelance.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#090D16] text-white selection:bg-amber-500/20 selection:text-amber-300 min-h-screen flex flex-col">
    <div class="auth-page flex-1 flex flex-col justify-between py-6 px-4">
        <!-- Top Bar -->
        <header class="w-full max-w-5xl mx-auto flex items-center justify-between py-2 text-xs font-semibold">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition group uppercase tracking-wider">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Retour à l'accueil</span>
            </a>

            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900/80 border border-slate-800 text-slate-300 text-xs shadow-inner">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Espace Sécurisé TutorLink</span>
            </div>
        </header>

        <!-- Main Card Slot -->
        <main class="w-full flex items-center justify-center my-auto py-8">
            {{ $slot }}
        </main>

        <!-- Bottom Security Note -->
        <footer class="w-full max-w-5xl mx-auto text-center py-4 text-xs text-slate-500">
            <div class="flex items-center justify-center gap-2 mb-1 text-slate-400">
                <svg class="w-4 h-4 text-amber-500/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Connexion chiffrée SSL 256-bit — &copy; {{ date('Y') }} TutorLink All Rights Reserved</span>
            </div>
            <div class="flex items-center justify-center gap-3 text-[11px] text-slate-500">
                <a href="#" class="hover:text-slate-400 transition">Conditions d'utilisation</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-slate-400 transition">Politique de confidentialité</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-slate-400 transition">Aide & Support</a>
            </div>
        </footer>
    </div>
</body>
</html>
