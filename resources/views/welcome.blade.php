<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TutorLink — Plateforme d'Apprentissage & Mentorat d'Excellence</title>
    <meta name="description" content="TutorLink connecte apprenants, tuteurs experts et coordinateurs sur un écosystème unifié et sécurisé.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#090D16] text-white font-sans min-h-screen selection:bg-amber-500/20 selection:text-amber-300">

    {{-- NAVBAR (Screenshot 1) --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-[#090D16]/90 backdrop-blur-xl border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 no-underline group">
                <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-[#0B101B] border border-amber-500/40 shadow-glow-sm">
                    <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                    </svg>
                </div>
                <span class="text-xl font-black tracking-tight text-white font-display">Tutor<span class="text-amber-500">Link</span></span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
                <a href="#accueil" class="text-amber-400 no-underline transition hover:text-amber-300">Accueil</a>
                <a href="#comment-ca-marche" class="text-slate-300 hover:text-white no-underline transition">Comment ça marche</a>
                <a href="#temoignages" class="text-slate-300 hover:text-white no-underline transition">Témoignages</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary px-6 py-2.5 rounded-full text-xs font-bold no-underline flex items-center gap-2">
                        <span>Tableau de bord</span>
                        <span>&rarr;</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:flex items-center gap-2 text-sm font-semibold text-slate-300 hover:text-white no-underline transition">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Connexion</span>
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary px-6 py-2.5 rounded-full text-xs font-bold no-underline flex items-center gap-2">
                        <span>S'inscrire</span>
                        <span>&rarr;</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- HERO SECTION (Screenshot 1) --}}
    <section id="accueil" class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
        <!-- Radial atmospheric glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[500px] bg-gradient-to-b from-amber-500/10 via-orange-500/5 to-transparent rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Column -->
                <div class="lg:col-span-6 space-y-6 text-left">
                    <span class="inline-block text-xs font-bold uppercase tracking-widest text-amber-500">
                        Découvrir la plateforme TutorLink
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight font-display leading-[1.1]">
                        Introduction à<br>TutorLink
                    </h1>
                    <p class="text-slate-400 text-sm sm:text-base leading-relaxed max-w-lg">
                        TutorLink est conçu pour faciliter le mentorat et l'apprentissage personnalisé. Connectez apprenants, tuteurs experts et coordinateurs sur un écosystème unifié et sécurisé.
                    </p>
                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <a href="{{ route('register', ['role' => 'tuteur']) }}" class="btn-primary px-7 py-3.5 rounded-full text-sm font-bold shadow-glow-amber no-underline inline-flex items-center gap-2">
                            <span>Créer une offre</span>
                        </a>
                        <a href="{{ route('demandes.index') }}" class="px-6 py-3.5 rounded-full text-sm font-semibold text-slate-300 hover:text-white bg-[#101726] border border-slate-800 hover:border-slate-700 transition no-underline inline-flex items-center gap-2">
                            <span>Explorer les demandes</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Right Column (Connected Architecture Diagram from Screenshot 1) -->
                <div class="lg:col-span-6 relative flex items-center justify-center">
                    <div class="relative w-full max-w-lg aspect-square flex items-center justify-center">
                        
                        <!-- SVG Connection Lines -->
                        <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 500 500" fill="none">
                            <!-- Dashed glow lines -->
                            <line x1="250" y1="90" x2="140" y2="210" stroke="#F59E0B" stroke-width="1.5" stroke-dasharray="4 4" stroke-opacity="0.6"/>
                            <line x1="250" y1="90" x2="360" y2="210" stroke="#F59E0B" stroke-width="1.5" stroke-dasharray="4 4" stroke-opacity="0.6"/>
                            <line x1="140" y1="210" x2="250" y2="360" stroke="#F59E0B" stroke-width="1.5" stroke-opacity="0.5"/>
                            <line x1="360" y1="210" x2="250" y2="360" stroke="#F59E0B" stroke-width="1.5" stroke-opacity="0.5"/>
                            
                            <!-- Glowing center connection circle -->
                            <circle cx="250" cy="210" r="3" fill="#F59E0B"/>
                        </svg>

                        <!-- Top Node: Choix de Cours -->
                        <div class="absolute top-8 left-1/2 -translate-x-1/2 flex flex-col items-center">
                            <div class="w-12 h-12 rounded-2xl bg-[#101726] border border-amber-500/40 shadow-glow-sm flex items-center justify-center text-amber-400 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-amber-400 tracking-wide">Choix de Cours</span>
                        </div>

                        <!-- Left Node: Apprenant -->
                        <div class="absolute left-6 top-1/2 -translate-y-1/2 flex flex-col items-center">
                            <div class="relative mb-2">
                                <div class="absolute -inset-1 rounded-full bg-amber-500/30 blur-sm animate-pulse"></div>
                                <div class="relative w-16 h-16 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-slate-950 shadow-xl">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2a5 5 0 105 5 5 5 0 00-5-5zm0 12c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5z"/>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-white tracking-wide">Apprenant</span>
                        </div>

                        <!-- Right Node: Administrateur -->
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 flex flex-col items-center">
                            <div class="relative mb-2">
                                <div class="absolute -inset-1 rounded-full bg-sky-500/30 blur-sm"></div>
                                <div class="relative w-16 h-16 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white shadow-xl">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2a5 5 0 105 5 5 5 0 00-5-5zm0 12c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5z"/>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-white tracking-wide">Administrateur</span>
                        </div>

                        <!-- Bottom Node: Session de Tutorat -->
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center">
                            <div class="w-12 h-12 rounded-2xl bg-[#101726] border border-amber-500/40 shadow-glow-sm flex items-center justify-center text-amber-400 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-amber-400 tracking-wide">Session de Tutorat</span>
                        </div>

                    </div>
                </div>

            </div>

            <!-- 3 Feature Cards Row (Screenshot 1) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16 pt-8">
                <!-- Card 1 -->
                <div class="card p-6 rounded-2xl bg-[#101726] border border-slate-800 hover:border-amber-500/40 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white mb-1">1. Créer une Offre</h3>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Publiez et configurez facilement vos matières, vos disponibilités et votre tarif horaire.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card p-6 rounded-2xl bg-[#101726] border border-slate-800 hover:border-amber-500/40 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white mb-1">2. Soumettre une Demande</h3>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Exprimez vos besoins spécifiques et recevez des propositions ciblées de mentors certifiés.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card p-6 rounded-2xl bg-[#101726] border border-slate-800 hover:border-amber-500/40 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white mb-1">Session de Tutorat</h3>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Bénéficiez d'un suivi interactif en direct avec tableau virtuel et feedback instantané.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- POURQUOI TUTORLINK ? (Screenshot 1) --}}
    <section class="py-12 border-y border-slate-800/80 bg-[#0B101B]">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-2xl sm:text-3xl font-black text-white font-display mb-6">
                Pourquoi TutorLink?
            </h2>
            <div class="flex flex-wrap items-center justify-center gap-8 text-xs sm:text-sm font-bold text-slate-300">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-400 shadow-glow-sm"></span>
                    <span>Premium & Professionnel</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-400 shadow-glow-sm"></span>
                    <span>Simple & Intuitif</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-400 shadow-glow-sm"></span>
                    <span>Tuteurs Certifiés</span>
                </div>
            </div>
        </div>
    </section>

    {{-- COMMENT ÇA MARCHE ? (Screenshot 1) --}}
    <section id="comment-ca-marche" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-6">
            
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full text-[11px] font-bold tracking-widest text-amber-500 uppercase bg-amber-500/10 border border-amber-500/20 mb-3">
                    Parcours intuitif en 4 étapes
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white font-display tracking-tight">
                    Comment ça marche ?
                </h2>
                <p class="text-slate-400 text-sm mt-3 leading-relaxed">
                    Une méthodologie fluide conçue pour connecter rapidement chaque étudiant au mentor idéal et accélérer sa réussite.
                </p>
            </div>

            <!-- 4 Cards Grid (Screenshot 1) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Step 01 -->
                <div class="card p-6 rounded-2xl bg-[#101726] border border-slate-800 flex flex-col justify-between hover:border-amber-500/40 transition-all duration-200 group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-black text-amber-500 font-display">01</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-white mb-2">Inscription & Profil</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Créez votre compte en quelques clics. Précisez vos matières, objectifs d'apprentissage ou compétences d'expertise.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-800">
                        <span class="text-[11px] font-semibold text-amber-400/90">Configuration en 2 min</span>
                    </div>
                </div>

                <!-- Step 02 -->
                <div class="card p-6 rounded-2xl bg-[#101726] border border-slate-800 flex flex-col justify-between hover:border-amber-500/40 transition-all duration-200 group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-black text-amber-500 font-display">02</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-white mb-2">Match Intelligent</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Notre algorithme vous propose instantanément des mentors qualifiés et compatibles avec votre rythme d'apprentissage.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-800">
                        <span class="text-[11px] font-semibold text-amber-400/90">Recommandations ciblées</span>
                    </div>
                </div>

                <!-- Step 03 -->
                <div class="card p-6 rounded-2xl bg-[#101726] border border-slate-800 flex flex-col justify-between hover:border-amber-500/40 transition-all duration-200 group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-black text-amber-500 font-display">03</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-white mb-2">Planification Flexible</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Choisissez vos créneaux en direct grâce au calendrier synchronisé. Réservation sans friction et rappels automatisés.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-800">
                        <span class="text-[11px] font-semibold text-amber-400/90">Disponibilités 7j/7</span>
                    </div>
                </div>

                <!-- Step 04 -->
                <div class="card p-6 rounded-2xl bg-[#101726] border border-slate-800 flex flex-col justify-between hover:border-amber-500/40 transition-all duration-200 group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-black text-amber-500 font-display">04</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                        </div>
                        <h3 class="text-base font-bold text-white mb-2">Session & Évaluation</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Vivez un cours immersif avec partage d'écran et tableau interactif. Suivez vos progrès grâce aux retours continus.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-800">
                        <span class="text-[11px] font-semibold text-amber-400/90">Progression mesurable</span>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- RETOURS D'EXPÉRIENCE (Screenshot 1) --}}
    <section id="temoignages" class="py-20 bg-[#0B101B] border-t border-slate-800/80">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full text-[11px] font-bold tracking-widest text-amber-500 uppercase bg-amber-500/10 border border-amber-500/20 mb-3">
                    Retours d'expérience
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white font-display tracking-tight">
                    Ce que disent nos apprenants et tuteurs
                </h2>
                <p class="text-slate-400 text-sm mt-3 leading-relaxed">
                    Découvrez l'impact concret de TutorLink à travers les parcours inspirants de notre communauté.
                </p>
            </div>

            <!-- 3 Testimonial Cards (Screenshot 1) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Testimonial 1 -->
                <div class="card p-7 rounded-2xl bg-[#101726] border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-300 italic leading-relaxed mb-6">
                            &laquo; En classe préparatoire, j'avais de vraies lacunes en thermodynamique. Grâce à mon mentor sur TutorLink, j'ai validé mes concours avec mention. La flexibilité est exceptionnelle. &raquo;
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
                        <div class="w-10 h-10 rounded-full bg-amber-500 text-slate-950 font-black text-xs flex items-center justify-center flex-shrink-0 shadow-md">
                            AB
                        </div>
                        <div>
                            <p class="font-bold text-white text-sm">Amina B.</p>
                            <p class="text-xs text-slate-400">Étudiante en Ingénierie</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="card p-7 rounded-2xl bg-[#101726] border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-300 italic leading-relaxed mb-6">
                            &laquo; En tant que tuteur, TutorLink structure tout : la gestion des offres, la sécurité des paiements et le tableau virtuel. Je me concentre uniquement sur la transmission du savoir. &raquo;
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
                        <div class="w-10 h-10 rounded-full bg-slate-700 text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-md">
                            YK
                        </div>
                        <div>
                            <p class="font-bold text-white text-sm">Youssef K.</p>
                            <p class="text-xs text-slate-400">Tuteur Maths & Data Science</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="card p-7 rounded-2xl bg-[#101726] border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-300 italic leading-relaxed mb-6">
                            &laquo; En pleine reconversion vers le développement web, avoir un mentor chevronné pour déboguer et orienter mes projets m'a fait gagner plus de six mois de travail acharné. &raquo;
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
                        <div class="w-10 h-10 rounded-full bg-orange-500 text-slate-950 font-black text-xs flex items-center justify-center flex-shrink-0 shadow-md">
                            SM
                        </div>
                        <div>
                            <p class="font-bold text-white text-sm">Sarah M.</p>
                            <p class="text-xs text-slate-400">Reconversion Professionnelle</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- FOOTER (Screenshot 1) --}}
    <footer class="bg-[#070A10] border-t border-slate-800/80 pt-16 pb-12 text-xs text-slate-400">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
                <!-- Brand col -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-[#0B101B] border border-amber-500/40 flex items-center justify-center text-amber-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-black text-white font-display">Tutor<span class="text-amber-500">Link</span></span>
                    </div>
                    <p class="text-slate-400 leading-relaxed text-xs">
                        L'écosystème de référence pour l'apprentissage sur mesure et le mentorat d'excellence.
                    </p>
                </div>

                <!-- Col 2 -->
                <div>
                    <h4 class="font-bold uppercase tracking-wider text-slate-200 mb-4 text-xs">Navigation</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#accueil" class="hover:text-amber-400 transition no-underline">Accueil</a></li>
                        <li><a href="#comment-ca-marche" class="hover:text-amber-400 transition no-underline">Comment ça marche</a></li>
                        <li><a href="#temoignages" class="hover:text-amber-400 transition no-underline">Témoignages</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-amber-400 transition no-underline">S'inscrire</a></li>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div>
                    <h4 class="font-bold uppercase tracking-wider text-slate-200 mb-4 text-xs">Matières & Rôles</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('demandes.index') }}" class="hover:text-amber-400 transition no-underline">Mathématiques & Sciences</a></li>
                        <li><a href="{{ route('demandes.index') }}" class="hover:text-amber-400 transition no-underline">Informatique & Code</a></li>
                        <li><a href="{{ route('register', ['role' => 'tuteur']) }}" class="hover:text-amber-400 transition no-underline">Devenir Tuteur</a></li>
                        <li><a href="{{ route('register', ['role' => 'apprenant']) }}" class="hover:text-amber-400 transition no-underline">Espace Apprenant</a></li>
                    </ul>
                </div>

                <!-- Col 4 -->
                <div>
                    <h4 class="font-bold uppercase tracking-wider text-slate-200 mb-4 text-xs">Légal & Contact</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-amber-400 transition no-underline">Conditions Générales</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition no-underline">Politique de Confidentialité</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition no-underline">Sécurité & Données</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition no-underline">Support 24/7</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
                <p>&copy; {{ date('Y') }} TutorLink Inc. Tous droits réservés.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-slate-300 transition no-underline">Twitter / X</a>
                    <a href="#" class="hover:text-slate-300 transition no-underline">LinkedIn</a>
                    <a href="#" class="hover:text-slate-300 transition no-underline">GitHub</a>
                    <a href="#" class="hover:text-slate-300 transition no-underline">Discord</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>