<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TutorLink — Plateforme d'Excellence pour le Soutien Scolaire & Supérieur au Maroc</title>
    <meta name="description" content="TutorLink connecte élèves, étudiants et professeurs particuliers qualifiés partout au Maroc. Cours particuliers à domicile et en ligne sans commission.">

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50 text-slate-900 font-sans min-h-screen flex flex-col selection:bg-blue-100 selection:text-blue-900">

    {{-- TOP NAVBAR --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            {{-- Brand Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3 no-underline group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shadow-sm group-hover:scale-105 transition-transform"
                     style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);">
                    TL
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-extrabold tracking-tight text-slate-900">Tutor<span class="text-blue-600">Link</span></span>
                    <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase -mt-1">Maroc</span>
                </div>
            </a>

            {{-- Center Navigation Links --}}
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="{{ route('demandes.index') }}" class="hover:text-blue-600 transition-colors no-underline">Explorer les cours</a>
                <a href="#matieres" class="hover:text-blue-600 transition-colors no-underline">Matières</a>
                <a href="#comment-ca-marche" class="hover:text-blue-600 transition-colors no-underline">Fonctionnement</a>
                <a href="#excellence" class="hover:text-blue-600 transition-colors no-underline">Pédagogie</a>
                <a href="#garanties" class="hover:text-blue-600 transition-colors no-underline">Garanties</a>
            </nav>

            {{-- Right CTA Actions --}}
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary flex items-center gap-2 no-underline">
                        <span>Mon Espace</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary no-underline">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary no-underline">
                        Commencer
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- HERO SECTION AVEC VISUEL PREMIUM --}}
    <section class="relative pt-12 pb-20 px-6 lg:pt-16 lg:pb-24 overflow-hidden bg-gradient-to-b from-white via-slate-50 to-slate-50 border-b border-slate-200/60">
        
        {{-- Ambient Glow --}}
        <div class="absolute top-0 right-1/4 w-[600px] h-[350px] bg-gradient-to-tr from-blue-100/40 via-indigo-50/30 to-transparent blur-3xl -z-10 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                {{-- Left Column: Copy & Search Form --}}
                <div class="lg:col-span-7 space-y-7 text-left">
                    
                    {{-- Trust Badge --}}
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Plateforme n°1 de soutien scolaire & universitaire au Maroc</span>
                    </div>

                    {{-- Main Headline --}}
                    <h1 class="text-4xl sm:text-5xl xl:text-6xl font-black text-slate-900 tracking-tight leading-[1.12]">
                        L'Excellence Académique à Portée de Main avec <span class="text-blue-600">TutorLink</span>
                    </h1>

                    {{-- Subtitle --}}
                    <p class="text-base sm:text-lg text-slate-600 font-medium leading-relaxed max-w-xl">
                        Connectez-vous directement avec des professeurs particuliers certifiés et passionnés. Du primaire aux classes préparatoires (CPGE) et études supérieures, bénéficiez d'un suivi méthodologique sans commission.
                    </p>

                    {{-- HERO LIVE SEARCH BAR --}}
                    <div class="pt-1 max-w-2xl">
                        <form action="{{ route('demandes.index') }}" method="GET" class="p-3 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5 grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                            
                            {{-- Input Matière --}}
                            <div class="sm:col-span-6 px-3 py-1">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Matière ou Discipline</label>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <input type="text" name="matiere" placeholder="Ex: Maths, Physique, SVT..." class="w-full text-sm font-semibold text-slate-900 placeholder-slate-400 border-none p-0 focus:ring-0 focus:outline-none">
                                </div>
                            </div>

                            <div class="hidden sm:block sm:col-span-1 h-8 border-r border-slate-200"></div>

                            {{-- Select Niveau --}}
                            <div class="sm:col-span-5 px-3 py-1">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Niveau Scolaire</label>
                                <select name="niveau" class="w-full text-sm font-semibold text-slate-900 border-none p-0 focus:ring-0 focus:outline-none bg-transparent cursor-pointer">
                                    <option value="">Tous les niveaux</option>
                                    <option value="Primaire">Primaire</option>
                                    <option value="Collège">Collège</option>
                                    <option value="Lycée">Lycée (Bac)</option>
                                    <option value="CPGE">CPGE / Prépa</option>
                                    <option value="Supérieur">Supérieur / Univ</option>
                                </select>
                            </div>

                            {{-- Submit Button Full Width --}}
                            <div class="sm:col-span-12 pt-1 border-t border-slate-100 flex items-center justify-between gap-3">
                                <span class="text-xs text-slate-400 font-medium hidden sm:inline">Mise en relation directe WhatsApp sans intermédiaire</span>
                                <button type="submit" class="btn-primary py-2.5 px-6 text-sm font-bold shadow-md w-full sm:w-auto ml-auto">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    Rechercher un cours
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Metrics Pill Strip --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 max-w-xl">
                        <div class="p-3.5 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                            <p class="text-2xl font-black text-slate-900 tracking-tight">98%</p>
                            <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Satisfaction élèves</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                            <p class="text-2xl font-black text-blue-600 tracking-tight">100%</p>
                            <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Tuteurs vérifiés</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                            <p class="text-2xl font-black text-amber-600 tracking-tight">&lt; 24h</p>
                            <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Premières offres</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                            <p class="text-2xl font-black text-emerald-600 tracking-tight">0 DH</p>
                            <p class="text-[11px] font-semibold text-slate-500 mt-0.5">Commission</p>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Image Hero Visuelle Haute Définition --}}
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        
                        {{-- Image Container --}}
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                            <img src="{{ asset('images/hero-tutoring.jpg') }}" 
                                 alt="Séance de tutorat et soutien scolaire TutorLink" 
                                 class="w-full h-[420px] object-cover object-center transform hover:scale-105 transition-transform duration-500">
                            
                            {{-- Soft Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>

                            {{-- Bottom Image Caption --}}
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <p class="text-xs font-bold uppercase tracking-wider text-blue-300">Pédagogie Positive</p>
                                <p class="text-sm font-extrabold text-white">Séances sur-mesure à domicile ou en ligne</p>
                            </div>
                        </div>

                        {{-- Floating Badge 1: Top Right --}}
                        <div class="absolute -top-4 -right-4 bg-white/95 backdrop-blur-md rounded-2xl p-3 shadow-lg border border-slate-200/80 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 font-bold flex items-center justify-center text-lg">
                                ★
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-900">4.9 / 5.0</p>
                                <p class="text-[10px] font-semibold text-slate-500">+1 200 avis certifiés</p>
                            </div>
                        </div>

                        {{-- Floating Badge 2: Bottom Left --}}
                        <div class="absolute -bottom-4 -left-4 bg-white/95 backdrop-blur-md rounded-2xl p-3 shadow-lg border border-slate-200/80 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-900">Profils Modérés</p>
                                <p class="text-[10px] font-semibold text-slate-500">100% tuteurs qualifiés</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- POPULAR SUBJECTS SECTION --}}
    <section id="matieres" class="py-20 px-6 bg-white border-b border-slate-200/80">
        <div class="max-w-6xl mx-auto space-y-12">
            <div class="text-center space-y-3">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Disciplines Populaires</p>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Matières & Concours d'Excellence</h2>
                <p class="text-base text-slate-600 max-w-xl mx-auto">Trouvez rapidement un enseignant hautement qualifié dans chaque filière.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @php
                    $disciplines = [
                        ['Mathématiques', 'Algèbre, Analyse, Géométrie', 'M', 'bg-blue-50 text-blue-600 border-blue-100'],
                        ['Physique - Chimie', 'Mécanique, Électricité, Ondes', 'PC', 'bg-indigo-50 text-indigo-600 border-indigo-100'],
                        ['SVT', 'Génétique, Géologie, Immunologie', 'SVT', 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                        ['Français & Philosophie', 'Dissertation, Analyse d\'œuvres', 'FR', 'bg-purple-50 text-purple-600 border-purple-100'],
                        ['Anglais & Espagnol', 'Expression écrite & orale, TOEIC', 'LANG', 'bg-sky-50 text-sky-600 border-sky-100'],
                        ['CPGE & Concours', 'MPSI, PCSI, CNC, Médecine', 'CPGE', 'bg-amber-50 text-amber-600 border-amber-100'],
                        ['Informatique', 'Python, Algorithmique, SQL', 'DEV', 'bg-teal-50 text-teal-600 border-teal-100'],
                        ['Économie & Gestion', 'Micro, Macro, Comptabilité', 'ÉCO', 'bg-rose-50 text-rose-600 border-rose-100'],
                    ];
                @endphp

                @foreach($disciplines as [$nom, $desc, $badge, $badgeStyle])
                    <a href="{{ route('demandes.index', ['matiere' => $nom]) }}" class="card card-hover p-5 flex flex-col justify-between no-underline group">
                        <div>
                            <div class="w-10 h-10 rounded-xl font-bold text-xs flex items-center justify-center border mb-3 {{ $badgeStyle }}">
                                {{ $badge }}
                            </div>
                            <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $nom }}</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $desc }}</p>
                        </div>
                        <div class="pt-4 mt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-600 group-hover:translate-x-1 transition-transform">
                            <span>Voir les annonces</span>
                            <span>→</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section id="comment-ca-marche" class="py-20 px-6 bg-slate-50 border-b border-slate-200/80">
        <div class="max-w-6xl mx-auto space-y-14">
            <div class="text-center space-y-3">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Simplicité & Rapidité</p>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Comment fonctionne TutorLink ?</h2>
                <p class="text-base text-slate-600 max-w-xl mx-auto">Une mise en relation directe, transparente et sécurisée en 3 étapes claires.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">

                {{-- Step 1 --}}
                <div class="card p-8 relative">
                    <div class="w-12 h-12 rounded-xl bg-blue-600 text-white font-black text-lg flex items-center justify-center mb-6 shadow-sm shadow-blue-500/20">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2.5">Publiez votre besoin</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Précisez la matière, le niveau académique (Primaire, Lycée, CPGE, Supérieur), votre ville et votre budget horaire estimé en dirhams (DH).
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="card p-8 relative">
                    <div class="w-12 h-12 rounded-xl bg-slate-900 text-white font-black text-lg flex items-center justify-center mb-6 shadow-sm">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2.5">Recevez les propositions</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Des professeurs qualifiés examinent votre demande et vous envoient leur offre personnalisée avec leur tarif horaire et leur démarche pédagogique.
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="card p-8 relative">
                    <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white font-black text-lg flex items-center justify-center mb-6 shadow-sm shadow-emerald-500/20">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2.5">Contactez & Réussissez</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Choisissez la meilleure offre pour débloquer immédiatement les coordonnées directes (WhatsApp & Téléphone) de l'enseignant.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION PÉDAGOGIE D'EXCELLENCE AVEC 2ÈME IMAGE --}}
    <section id="excellence" class="py-20 px-6 bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                {{-- Image de gauche --}}
                <div class="lg:col-span-6">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-slate-100">
                        <img src="{{ asset('images/tutor-mentorship.jpg') }}" 
                             alt="Excellence pédagogique et mentorat TutorLink" 
                             class="w-full h-[400px] object-cover object-center">
                        <div class="absolute bottom-4 left-4 bg-slate-900/85 backdrop-blur-md px-4 py-2.5 rounded-xl text-white">
                            <p class="text-xs font-bold text-emerald-400">Suivi Rigoureux</p>
                            <p class="text-xs font-semibold">Préparation intensive au Bac & Concours nationaux</p>
                        </div>
                    </div>
                </div>

                {{-- Contenu de droite --}}
                <div class="lg:col-span-6 space-y-6 text-left">
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Accompagnement Sur-Mesure</p>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        Une Méthodologie Conçue pour Transformer Vos Résultats
                    </h2>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                        Chaque élève a son propre rythme d'assimilation. Sur TutorLink, nos professeurs particuliers adoptent une démarche individualisée : comblement des lacunes, entraînement intensif sur les annales d'examens et acquisition de réflexes méthodologiques solides.
                    </p>

                    <div class="space-y-3.5 pt-2">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">✓</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Diagnostic Initial des Besoins</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Identification précise des points de blocage dès la première séance de cours.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">✓</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Préparation aux Épreuves & Concours</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Sujets types, gestion du temps et entraînements ciblés pour le Bac et les écoles supérieures.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">✓</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Communication Fluide WhatsApp</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Possibilité d'envoyer un exercice bloquant entre deux séances pour un suivi continu.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('demandes.index') }}" class="btn-primary no-underline">
                            Découvrir les tuteurs disponibles
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- WHY CHOOSE TUTORLINK / TRUST PILLARS --}}
    <section id="garanties" class="py-20 px-6 bg-slate-50 border-b border-slate-200/80">
        <div class="max-w-6xl mx-auto space-y-12">
            <div class="text-center space-y-3">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Sérieux & Confiance</p>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Pourquoi choisir TutorLink ?</h2>
                <p class="text-base text-slate-600 max-w-xl mx-auto">Une plateforme pensée pour les réalités académiques et les familles au Maroc.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="card p-8 space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Modération Systématique</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Toutes les annonces et candidatures sont soigneusement vérifiées par notre équipe avant publication pour garantir un environnement respectueux et de qualité.
                    </p>
                </div>

                <div class="card p-8 space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Avis 100% Authentifiés</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Seuls les élèves ayant effectivement validé et terminé un cours avec un enseignant peuvent publier une évaluation certifiée avec note sur 5.
                    </p>
                </div>

                <div class="card p-8 space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Échanges Libres & WhatsApp</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Aucun intermédiaire contraignant. Dès l'acceptation de l'offre, communiquez directement par WhatsApp ou appel pour fixer vos horaires.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- CALL TO ACTION BANNER --}}
    <section class="py-16 px-6 bg-slate-900 text-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto text-center space-y-6 relative z-10">
            <h2 class="text-3xl sm:text-4xl font-black tracking-tight">
                Prêt à accélérer votre parcours académique ?
            </h2>
            <p class="text-slate-300 text-base max-w-xl mx-auto font-medium">
                Rejoignez des centaines d'élèves et de tuteurs passionnés partout au Maroc. Inscription rapide et 100% gratuite.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                @auth
                    <a href="{{ route('demandes.create') }}" class="btn-primary px-8 py-3.5 text-base font-bold shadow-lg no-underline inline-block">
                        Publier une demande de cours
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary px-8 py-3.5 text-base font-bold shadow-lg no-underline inline-block">
                        Créer mon compte maintenant
                    </a>
                    <a href="{{ route('demandes.index') }}" class="btn-secondary px-8 py-3.5 text-base font-bold no-underline inline-block !bg-slate-800 !text-white !border-slate-700 hover:!bg-slate-700">
                        Consulter les demandes
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- COMPREHENSIVE FOOTER --}}
    <footer class="mt-auto py-12 px-6 bg-white border-t border-slate-200 text-sm text-slate-500">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            <div class="space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-black text-xs flex items-center justify-center">TL</div>
                    <span class="font-extrabold text-slate-900 text-base">TutorLink Maroc</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    La plateforme marocaine dédiée à l'excellence éducative et à la mise en relation bienveillante entre tuteurs et apprenants.
                </p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-3">Matières Clés</p>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('demandes.index', ['matiere' => 'Mathématiques']) }}" class="hover:text-blue-600 transition-colors no-underline">Mathématiques</a></li>
                    <li><a href="{{ route('demandes.index', ['matiere' => 'Physique - Chimie']) }}" class="hover:text-blue-600 transition-colors no-underline">Physique - Chimie</a></li>
                    <li><a href="{{ route('demandes.index', ['matiere' => 'SVT']) }}" class="hover:text-blue-600 transition-colors no-underline">Sciences de la Vie et de la Terre</a></li>
                    <li><a href="{{ route('demandes.index', ['matiere' => 'Anglais']) }}" class="hover:text-blue-600 transition-colors no-underline">Langues Vivantes</a></li>
                </ul>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-3">Niveaux & Cursus</p>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('demandes.index', ['niveau' => 'Collège']) }}" class="hover:text-blue-600 transition-colors no-underline">Collège (1ère à 3ème AC)</a></li>
                    <li><a href="{{ route('demandes.index', ['niveau' => 'Lycée']) }}" class="hover:text-blue-600 transition-colors no-underline">Lycée (Tronc Commun & Bac)</a></li>
                    <li><a href="{{ route('demandes.index', ['niveau' => 'CPGE']) }}" class="hover:text-blue-600 transition-colors no-underline">Classes Préparatoires (CPGE)</a></li>
                    <li><a href="{{ route('demandes.index', ['niveau' => 'Supérieur']) }}" class="hover:text-blue-600 transition-colors no-underline">Universités & Grandes Écoles</a></li>
                </ul>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-3">Espace Membre</p>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('login') }}" class="hover:text-blue-600 transition-colors no-underline">Connexion</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-blue-600 transition-colors no-underline">Inscription Apprenant</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-blue-600 transition-colors no-underline">Devenir Tuteur</a></li>
                    <li><a href="{{ route('demandes.index') }}" class="hover:text-blue-600 transition-colors no-underline">Toutes les annonces</a></li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} TutorLink Maroc. Tous droits réservés.</p>
            <p class="flex items-center gap-4">
                <span>Rabat · Casablanca · Marrakech · Fès · Tanger · En ligne</span>
            </p>
        </div>
    </footer>

</body>
</html>
