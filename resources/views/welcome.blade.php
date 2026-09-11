<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TutorLink — Plateforme d'Excellence & Soutien Académique au Maroc</title>
    <meta name="description" content="TutorLink connecte élèves, étudiants et professeurs particuliers d'exception partout au Maroc. Modèle direct, transparent et sans intermédiaire.">

    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#fafaf9] text-[#121212] font-sans min-h-screen flex flex-col selection:bg-[#121212] selection:text-white">

    {{-- TOP NAVBAR ÉDITORIALE --}}
    <header class="sticky top-0 z-50 bg-[#fafaf9]/90 backdrop-blur-md border-b border-[#e7e7e4] transition-all">
        <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">

            {{-- Brand Monogram --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3 no-underline group">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white font-black text-xs tracking-widest uppercase bg-[#121212] shadow-xs group-hover:scale-105 transition-transform relative">
                    TL
                    <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-amber-500"></span>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-black tracking-tight text-[#121212] font-display">Tutor<span class="text-neutral-500 font-medium">Link</span></span>
                    <span class="text-[9px] font-bold tracking-widest text-neutral-400 uppercase -mt-0.5">Maroc · Studio</span>
                </div>
            </a>

            {{-- Center Navigation Links --}}
            <nav class="hidden md:flex items-center gap-8 text-xs font-semibold uppercase tracking-wider text-neutral-500">
                <a href="{{ route('demandes.index') }}" class="hover:text-[#121212] transition-colors no-underline">Explorer</a>
                <a href="#matieres" class="hover:text-[#121212] transition-colors no-underline">Disciplines</a>
                <a href="#comment-ca-marche" class="hover:text-[#121212] transition-colors no-underline">Méthode</a>
                <a href="#excellence" class="hover:text-[#121212] transition-colors no-underline">Pédagogie</a>
                <a href="#garanties" class="hover:text-[#121212] transition-colors no-underline">Garanties</a>
            </nav>

            {{-- Right CTA Actions --}}
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary flex items-center gap-2 no-underline">
                        <span>Mon Espace</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary no-underline">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary no-underline">
                        S'inscrire
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- HERO SECTION ÉPURÉE SWISS --}}
    <section class="relative pt-16 pb-20 px-6 lg:pt-20 lg:pb-24 overflow-hidden border-b border-[#e7e7e4]">
        
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">
                
                {{-- Left Column: Typography & Search --}}
                <div class="lg:col-span-7 space-y-7 text-left">
                    
                    {{-- Minimalist Badge --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide bg-neutral-100 text-neutral-800 border border-neutral-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span>Plateforme académique d'excellence & mentorat</span>
                    </div>

                    {{-- Architectural Title --}}
                    <h1 class="text-4xl sm:text-5xl xl:text-6xl font-black text-[#121212] tracking-tight leading-[1.08] font-display">
                        L'accompagnement scolaire, <br>
                        <span class="text-neutral-500 font-normal italic">redéfini avec exigence.</span>
                    </h1>

                    {{-- Clean Subtitle --}}
                    <p class="text-base text-neutral-600 font-normal leading-relaxed max-w-lg">
                        Mise en relation directe avec les professeurs particuliers les plus qualifiés du Royaume. Du collège aux classes préparatoires (CPGE), un suivi rigoureux, sans commission.
                    </p>

                    {{-- LIVE SEARCH BAR MINIMALISTE --}}
                    <div class="pt-2 max-w-xl">
                        <form action="{{ route('demandes.index') }}" method="GET" class="p-2 bg-white rounded-xl border border-[#e7e7e4] shadow-xs grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                            
                            {{-- Input Matière --}}
                            <div class="sm:col-span-6 px-3 py-1">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-0.5">Matière</label>
                                <input type="text" name="matiere" placeholder="Maths, Physique, SVT..." class="w-full text-sm font-semibold text-neutral-900 placeholder-neutral-400 border-none p-0 focus:ring-0 focus:outline-none">
                            </div>

                            <div class="hidden sm:block sm:col-span-1 h-6 border-r border-[#e7e7e4]"></div>

                            {{-- Select Niveau --}}
                            <div class="sm:col-span-5 px-3 py-1">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-0.5">Niveau</label>
                                <select name="niveau" class="w-full text-sm font-semibold text-neutral-900 border-none p-0 focus:ring-0 focus:outline-none bg-transparent cursor-pointer">
                                    <option value="">Tous les niveaux</option>
                                    <option value="Primaire">Primaire</option>
                                    <option value="Collège">Collège</option>
                                    <option value="Lycée">Lycée (Bac)</option>
                                    <option value="CPGE">CPGE / Prépa</option>
                                    <option value="Supérieur">Supérieur / Univ</option>
                                </select>
                            </div>

                            {{-- Submit Button --}}
                            <div class="sm:col-span-12 pt-2 border-t border-neutral-100 flex items-center justify-between gap-3">
                                <span class="text-[11px] text-neutral-400 hidden sm:inline">Contact direct WhatsApp après acceptation</span>
                                <button type="submit" class="btn-primary w-full sm:w-auto ml-auto">
                                    Rechercher
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Minimalist Metrics Strip --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 max-w-xl">
                        <div class="p-3 rounded-lg bg-white border border-[#e7e7e4]">
                            <p class="text-xl font-bold text-neutral-900 font-display">98%</p>
                            <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider mt-0.5">Satisfaction</p>
                        </div>
                        <div class="p-3 rounded-lg bg-white border border-[#e7e7e4]">
                            <p class="text-xl font-bold text-neutral-900 font-display">100%</p>
                            <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider mt-0.5">Modérés</p>
                        </div>
                        <div class="p-3 rounded-lg bg-white border border-[#e7e7e4]">
                            <p class="text-xl font-bold text-amber-600 font-display">&lt; 24h</p>
                            <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider mt-0.5">Délai moyen</p>
                        </div>
                        <div class="p-3 rounded-lg bg-white border border-[#e7e7e4]">
                            <p class="text-xl font-bold text-neutral-900 font-display">0 DH</p>
                            <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider mt-0.5">Commission</p>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Image Hero Visuelle Monochrome Haute Définition --}}
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        
                        {{-- Image Container avec bordure fine architecturale --}}
                        <div class="relative rounded-2xl overflow-hidden border border-[#e7e7e4] bg-white p-2 shadow-xs">
                            <img src="{{ asset('images/hero-tutoring.jpg') }}" 
                                 alt="Séance d'accompagnement académique TutorLink" 
                                 class="w-full h-[400px] object-cover object-center rounded-xl">
                            
                            {{-- Minimalist bottom tag --}}
                            <div class="absolute bottom-6 left-6 right-6 p-3 bg-white/95 backdrop-blur-md rounded-lg border border-[#e7e7e4] text-xs">
                                <p class="font-bold text-neutral-900 font-display">Séance Individuelle & Suivi Régulier</p>
                                <p class="text-neutral-500 text-[11px] mt-0.5">À domicile ou en visioconférence partout au Maroc</p>
                            </div>
                        </div>

                        {{-- Micro Floating Badge --}}
                        <div class="absolute -top-3 -right-3 bg-white rounded-lg p-2.5 shadow-sm border border-[#e7e7e4] flex items-center gap-2">
                            <span class="text-amber-500 text-sm">★</span>
                            <span class="text-xs font-bold text-neutral-900">4.9 / 5.0</span>
                            <span class="text-[10px] text-neutral-400">· 1.2k avis</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- POPULAR SUBJECTS SECTION --}}
    <section id="matieres" class="py-20 px-6 bg-white border-b border-[#e7e7e4]">
        <div class="max-w-6xl mx-auto space-y-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Disciplines</p>
                    <h2 class="text-3xl font-black text-neutral-900 tracking-tight font-display mt-1">Matières & Concours d'Excellence</h2>
                </div>
                <p class="text-xs text-neutral-500 max-w-md">Trouvez rapidement un enseignant hautement qualifié pour un suivi hebdomadaire ou une révision intensive.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @php
                    $disciplines = [
                        ['Mathématiques', 'Algèbre, Analyse, Géométrie', 'M'],
                        ['Physique - Chimie', 'Mécanique, Électricité, Ondes', 'PC'],
                        ['SVT', 'Génétique, Géologie, Immunologie', 'SVT'],
                        ['Français & Philosophie', 'Dissertation, Analyse d\'œuvres', 'FR'],
                        ['Langues Vivantes', 'Anglais, Espagnol, Expression', 'EN'],
                        ['CPGE & Concours', 'MPSI, PCSI, CNC, Médecine', 'CPGE'],
                        ['Informatique', 'Python, Algorithmique, SQL', 'DEV'],
                        ['Économie & Gestion', 'Micro, Macro, Comptabilité', 'ÉCO'],
                    ];
                @endphp

                @foreach($disciplines as [$nom, $desc, $badge])
                    <a href="{{ route('demandes.index', ['matiere' => $nom]) }}" class="card card-hover p-5 flex flex-col justify-between no-underline group">
                        <div>
                            <div class="w-8 h-8 rounded-md font-bold text-xs flex items-center justify-center border border-[#e7e7e4] bg-[#fafaf9] text-neutral-800 mb-3">
                                {{ $badge }}
                            </div>
                            <h3 class="text-sm font-bold text-neutral-900 group-hover:text-black transition-colors font-display">{{ $nom }}</h3>
                            <p class="text-xs text-neutral-500 mt-1 leading-relaxed">{{ $desc }}</p>
                        </div>
                        <div class="pt-4 mt-4 border-t border-[#f5f5f4] flex items-center justify-between text-xs font-semibold text-neutral-700 group-hover:text-black">
                            <span>Consulter</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section id="comment-ca-marche" class="py-20 px-6 bg-[#fafaf9] border-b border-[#e7e7e4]">
        <div class="max-w-6xl mx-auto space-y-12">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Processus</p>
                <h2 class="text-3xl font-black text-neutral-900 tracking-tight font-display mt-1">Le fonctionnement en 3 temps</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Step 1 --}}
                <div class="card p-7">
                    <p class="text-xs font-mono text-neutral-400 mb-4 font-bold">01 / DÉPÔT</p>
                    <h3 class="text-lg font-bold text-neutral-900 mb-2 font-display">Publiez votre besoin</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed">
                        Précisez la matière, le niveau académique (Collège, Lycée, Supérieur), votre ville et votre budget horaire estimé en dirhams (DH).
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="card p-7">
                    <p class="text-xs font-mono text-neutral-400 mb-4 font-bold">02 / PROPOSITIONS</p>
                    <h3 class="text-lg font-bold text-neutral-900 mb-2 font-display">Recevez les offres</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed">
                        Des enseignants certifiés examinent votre demande et formulent leur offre personnalisée avec leur tarif horaire et leur pédagogie.
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="card p-7">
                    <p class="text-xs font-mono text-neutral-400 mb-4 font-bold">03 / CONTACT</p>
                    <h3 class="text-lg font-bold text-neutral-900 mb-2 font-display">Échangez directement</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed">
                        Retenez la meilleure proposition pour débloquer immédiatement les coordonnées directes (WhatsApp & Téléphone) de l'enseignant.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION PÉDAGOGIE D'EXCELLENCE AVEC 2ÈME IMAGE --}}
    <section id="excellence" class="py-20 px-6 bg-white border-b border-[#e7e7e4]">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                {{-- Image de gauche --}}
                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden border border-[#e7e7e4] bg-[#fafaf9] p-2">
                        <img src="{{ asset('images/tutor-mentorship.jpg') }}" 
                             alt="Excellence pédagogique et mentorat TutorLink" 
                             class="w-full h-[380px] object-cover object-center rounded-xl">
                    </div>
                </div>

                {{-- Contenu de droite --}}
                <div class="lg:col-span-6 space-y-5 text-left">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Approche Pédagogique</p>
                    <h2 class="text-3xl font-black text-neutral-900 tracking-tight leading-snug font-display">
                        Une démarche sur-mesure tournée vers les résultats
                    </h2>
                    <p class="text-neutral-600 text-xs sm:text-sm leading-relaxed">
                        Chaque élève progresse à son rythme. Les professeurs sur TutorLink instaurent un diagnostic initial rigoureux pour cibler les lacunes, développer la méthodologie d'examen et bâtir une solide autonomie de travail.
                    </p>

                    <div class="space-y-3 pt-2">
                        <div class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-900 mt-2 flex-shrink-0"></span>
                            <div>
                                <h4 class="text-xs font-bold text-neutral-900">Diagnostic initial individualisé</h4>
                                <p class="text-[11px] text-neutral-500 mt-0.5">Identification des points de blocage dès la première séance.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-900 mt-2 flex-shrink-0"></span>
                            <div>
                                <h4 class="text-xs font-bold text-neutral-900">Entraînement sur annales & concours</h4>
                                <p class="text-[11px] text-neutral-500 mt-0.5">Préparation intensive au Baccalauréat et aux écoles supérieures.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-900 mt-2 flex-shrink-0"></span>
                            <div>
                                <h4 class="text-xs font-bold text-neutral-900">Échanges directs sans filtre</h4>
                                <p class="text-[11px] text-neutral-500 mt-0.5">Suivi continu et liberté d'organisation par WhatsApp.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3">
                        <a href="{{ route('demandes.index') }}" class="btn-primary no-underline">
                            Consulter les annonces ouvertes
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- WHY CHOOSE TUTORLINK / TRUST PILLARS --}}
    <section id="garanties" class="py-20 px-6 bg-[#fafaf9] border-b border-[#e7e7e4]">
        <div class="max-w-6xl mx-auto space-y-10">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Engagements</p>
                <h2 class="text-3xl font-black text-neutral-900 tracking-tight font-display mt-1">Nos piliers de confiance</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="card p-7 space-y-3">
                    <div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center text-xs font-bold text-neutral-800">
                        01
                    </div>
                    <h3 class="text-base font-bold text-neutral-900 font-display">Modération Préalable</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed">
                        Toutes les annonces et candidatures sont soigneusement vérifiées par notre équipe avant d'apparaître publiquement sur la plateforme.
                    </p>
                </div>

                <div class="card p-7 space-y-3">
                    <div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center text-xs font-bold text-neutral-800">
                        02
                    </div>
                    <h3 class="text-base font-bold text-neutral-900 font-display">Avis 100% Authentifiés</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed">
                        Seuls les élèves ayant validé et terminé un cours avec un professeur peuvent soumettre une évaluation notée sur 5 étoiles.
                    </p>
                </div>

                <div class="card p-7 space-y-3">
                    <div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center text-xs font-bold text-neutral-800">
                        03
                    </div>
                    <h3 class="text-base font-bold text-neutral-900 font-display">Contact Direct & Transparent</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed">
                        Zéro commission cachée. Dès l'offre acceptée, communiquez directement par WhatsApp ou téléphone pour fixer vos créneaux.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- CALL TO ACTION BANNER ÉDITORIAL --}}
    <section class="py-16 px-6 bg-[#121212] text-white">
        <div class="max-w-4xl mx-auto text-center space-y-5">
            <h2 class="text-3xl sm:text-4xl font-black tracking-tight font-display text-white">
                Prêt à accélérer votre parcours académique ?
            </h2>
            <p class="text-neutral-400 text-xs sm:text-sm max-w-lg mx-auto font-normal leading-relaxed">
                Rejoignez des centaines d'élèves et de tuteurs passionnés partout au Maroc. Inscription rapide et 100% gratuite.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                @auth
                    <a href="{{ route('demandes.create') }}" class="btn-primary !bg-white !text-neutral-900 hover:!bg-neutral-100 no-underline inline-block px-7 py-3">
                        Publier une demande
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary !bg-white !text-neutral-900 hover:!bg-neutral-100 no-underline inline-block px-7 py-3">
                        Créer mon compte
                    </a>
                    <a href="{{ route('demandes.index') }}" class="btn-secondary !bg-transparent !text-white !border-neutral-700 hover:!bg-neutral-800 no-underline inline-block px-7 py-3">
                        Explorer les demandes
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- COMPREHENSIVE FOOTER ÉDITORIAL --}}
    <footer class="mt-auto py-12 px-6 bg-white border-t border-[#e7e7e4] text-xs text-neutral-500">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            <div class="space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-md bg-[#121212] text-white font-black text-xs flex items-center justify-center">TL</div>
                    <span class="font-extrabold text-neutral-900 text-sm font-display">TutorLink Maroc</span>
                </div>
                <p class="text-[11px] text-neutral-500 leading-relaxed">
                    Plateforme académique dédiée à la mise en relation rigoureuse et bienveillante entre tuteurs qualifiés et apprenants.
                </p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 mb-3">Matières</p>
                <ul class="space-y-2 text-[11px]">
                    <li><a href="{{ route('demandes.index', ['matiere' => 'Mathématiques']) }}" class="hover:text-black transition-colors no-underline">Mathématiques</a></li>
                    <li><a href="{{ route('demandes.index', ['matiere' => 'Physique - Chimie']) }}" class="hover:text-black transition-colors no-underline">Physique - Chimie</a></li>
                    <li><a href="{{ route('demandes.index', ['matiere' => 'SVT']) }}" class="hover:text-black transition-colors no-underline">Sciences de la Vie et de la Terre</a></li>
                    <li><a href="{{ route('demandes.index', ['matiere' => 'Anglais']) }}" class="hover:text-black transition-colors no-underline">Langues Vivantes</a></li>
                </ul>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 mb-3">Cursus</p>
                <ul class="space-y-2 text-[11px]">
                    <li><a href="{{ route('demandes.index', ['niveau' => 'Collège']) }}" class="hover:text-black transition-colors no-underline">Collège (1ère à 3ème AC)</a></li>
                    <li><a href="{{ route('demandes.index', ['niveau' => 'Lycée']) }}" class="hover:text-black transition-colors no-underline">Lycée (Tronc Commun & Bac)</a></li>
                    <li><a href="{{ route('demandes.index', ['niveau' => 'CPGE']) }}" class="hover:text-black transition-colors no-underline">Classes Préparatoires (CPGE)</a></li>
                    <li><a href="{{ route('demandes.index', ['niveau' => 'Supérieur']) }}" class="hover:text-black transition-colors no-underline">Universités & Grandes Écoles</a></li>
                </ul>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 mb-3">Espace Membre</p>
                <ul class="space-y-2 text-[11px]">
                    <li><a href="{{ route('login') }}" class="hover:text-black transition-colors no-underline">Connexion</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-black transition-colors no-underline">Inscription Apprenant</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-black transition-colors no-underline">Devenir Tuteur</a></li>
                    <li><a href="{{ route('demandes.index') }}" class="hover:text-black transition-colors no-underline">Toutes les annonces</a></li>
                </ul>
            </div>
        </div>

        <div class="max-w-6xl mx-auto pt-6 border-t border-[#f5f5f4] flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-neutral-400">
            <p>&copy; {{ date('Y') }} TutorLink Maroc. Tous droits réservés.</p>
            <p>Rabat · Casablanca · Marrakech · Fès · Tanger · En ligne</p>
        </div>
    </footer>

</body>
</html>
