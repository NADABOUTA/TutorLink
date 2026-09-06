<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TutorLink — Plateforme de Tuteurs Freelance au Maroc</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased" style="background: rgb(15, 15, 26); color: rgb(248, 250, 252); font-family: 'Inter', sans-serif; min-height: 100vh; overflow-x: hidden;">

    {{-- Background Glow Effects --}}
    <div style="position: absolute; top: 0; left: 20%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(15, 15, 26, 0) 70%); pointer-events: none; filter: blur(60px); z-index: 0;"></div>
    <div style="position: absolute; top: 300px; right: 10%; width: 450px; height: 450px; background: radial-gradient(circle, rgba(168, 85, 247, 0.12) 0%, rgba(15, 15, 26, 0) 70%); pointer-events: none; filter: blur(70px); z-index: 0;"></div>

    <div class="relative z-10 flex flex-col min-h-screen">

        {{-- NAVBAR --}}
        <header class="sticky top-0 z-50 backdrop-blur-md" style="background: rgba(15, 15, 26, 0.8); border-bottom: 1px solid rgba(255, 255, 255, 0.06);">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3" style="text-decoration: none;">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shadow-lg"
                         style="background: linear-gradient(135deg, rgb(99, 102, 241), rgb(168, 85, 247)); box-shadow: 0 4px 14px rgba(99,102,241,0.4);">
                        🎓
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight text-white">Tutor<span style="color: rgb(99, 102, 241);">Link</span></span>
                        <span class="block text-[10px] font-semibold tracking-wider uppercase" style="color: rgb(148, 163, 184);">Maroc</span>
                    </div>
                </a>

                {{-- Center links --}}
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium" style="color: rgb(203, 213, 225);">
                    <a href="{{ route('demandes.index') }}" class="hover:text-white transition-colors" style="text-decoration: none;">Parcourir les demandes</a>
                    <a href="#comment-ca-marche" class="hover:text-white transition-colors" style="text-decoration: none;">Comment ça marche</a>
                    <a href="#avantages" class="hover:text-white transition-colors" style="text-decoration: none;">Pourquoi TutorLink</a>
                </nav>

                {{-- Auth Buttons --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary flex items-center gap-2" style="text-decoration: none;">
                            <span>Tableau de bord</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary" style="text-decoration: none;">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary" style="text-decoration: none;">
                            Commencer
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        {{-- HERO SECTION --}}
        <section class="relative pt-16 pb-20 px-6 text-center lg:pt-24 lg:pb-32">
            <div class="max-w-4xl mx-auto space-y-8">
                
                {{-- Pill badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold"
                     style="background: rgba(99, 102, 241, 0.12); border: 1px solid rgba(99, 102, 241, 0.3); color: rgb(165, 180, 252);">
                    <span class="flex h-2 w-2 rounded-full" style="background: rgb(99, 102, 241);"></span>
                    Plateforme n°1 de soutien scolaire & supérieur au Maroc
                </div>

                {{-- Main Title --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight">
                    Trouvez le <span style="background: linear-gradient(135deg, rgb(129, 140, 248), rgb(192, 132, 252)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Tuteur Idéal</span> pour votre Réussite
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed" style="color: rgb(148, 163, 184);">
                    TutorLink connecte directement les apprenants et étudiants avec les meilleurs tuteurs freelance. Cours particuliers, préparation aux concours et suivi méthodologique sur-mesure.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="{{ route('demandes.index') }}" class="btn-primary w-full sm:w-auto px-8 py-4 text-base font-bold shadow-xl"
                       style="box-shadow: 0 10px 25px rgba(99, 102, 241, 0.35); text-decoration: none;">
                        🔍 Découvrir les demandes
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn-secondary w-full sm:w-auto px-8 py-4 text-base font-semibold"
                           style="text-decoration: none;">
                            ✨ Créer un compte gratuit
                        </a>
                    @else
                        <a href="{{ route('demandes.create') }}" class="btn-secondary w-full sm:w-auto px-8 py-4 text-base font-semibold"
                           style="text-decoration: none;">
                            ✍️ Publier une demande
                        </a>
                    @endguest
                </div>

                {{-- Metrics Strip --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-12 max-w-3xl mx-auto">
                    <div class="p-4 rounded-2xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                        <p class="text-2xl lg:text-3xl font-black text-white">98%</p>
                        <p class="text-xs mt-1" style="color: rgb(148,163,184);">Satisfaction élèves</p>
                    </div>
                    <div class="p-4 rounded-2xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                        <p class="text-2xl lg:text-3xl font-black" style="color: rgb(99,102,241);">100%</p>
                        <p class="text-xs mt-1" style="color: rgb(148,163,184);">Demandes modérées</p>
                    </div>
                    <div class="p-4 rounded-2xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                        <p class="text-2xl lg:text-3xl font-black" style="color: rgb(168,85,247);">&lt; 24h</p>
                        <p class="text-xs mt-1" style="color: rgb(148,163,184);">Temps de réponse moyen</p>
                    </div>
                    <div class="p-4 rounded-2xl" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                        <p class="text-2xl lg:text-3xl font-black" style="color: rgb(34,197,94);">0 DH</p>
                        <p class="text-xs mt-1" style="color: rgb(148,163,184);">Frais d'intermédiaire</p>
                    </div>
                </div>

            </div>
        </section>

        {{-- FEATURES SECTION --}}
        <section id="avantages" class="py-20 px-6" style="background: rgba(255, 255, 255, 0.01); border-top: 1px solid rgba(255, 255, 255, 0.05);">
            <div class="max-w-6xl mx-auto space-y-12">
                <div class="text-center space-y-3">
                    <h2 class="text-3xl font-bold text-white tracking-tight">Une expérience pensée pour votre réussite</h2>
                    <p class="text-sm max-w-xl mx-auto" style="color: rgb(148, 163, 184);">Que vous soyez élève en quête de progression ou tuteur désireux de partager votre savoir.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Feature 1 --}}
                    <div class="card space-y-4 hover:-translate-y-1 transition-transform duration-200">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl" style="background: rgba(99, 102, 241, 0.15);">
                            🎯
                        </div>
                        <h3 class="text-lg font-bold text-white">Demandes ciblées</h3>
                        <p class="text-sm leading-relaxed" style="color: rgb(148, 163, 184);">
                            Précisez votre matière, niveau (Primaire au Supérieur), votre budget prévisionnel et décrivez précisément vos attentes.
                        </p>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="card space-y-4 hover:-translate-y-1 transition-transform duration-200">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl" style="background: rgba(168, 85, 247, 0.15);">
                            ⭐
                        </div>
                        <h3 class="text-lg font-bold text-white">Avis & Notes certifiés</h3>
                        <p class="text-sm leading-relaxed" style="color: rgb(148, 163, 184);">
                            Consultez les notes moyennes et retours authentiques laissés par les apprenants ayant réellement bénéficié des cours.
                        </p>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="card space-y-4 hover:-translate-y-1 transition-transform duration-200">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl" style="background: rgba(34, 197, 94, 0.15);">
                            🛡️
                        </div>
                        <h3 class="text-lg font-bold text-white">Modération & Sécurité</h3>
                        <p class="text-sm leading-relaxed" style="color: rgb(148, 163, 184);">
                            Chaque demande est revue par notre équipe d'administration. Coordonnées protégées et débloquées uniquement lors de l'accord mutuel.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- HOW IT WORKS --}}
        <section id="comment-ca-marche" class="py-20 px-6">
            <div class="max-w-5xl mx-auto space-y-12">
                <div class="text-center space-y-3">
                    <h2 class="text-3xl font-bold text-white tracking-tight">Comment ça marche ?</h2>
                    <p class="text-sm max-w-xl mx-auto" style="color: rgb(148, 163, 184);">Un processus fluide en 3 étapes simples.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                    <div class="space-y-3 text-center">
                        <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center text-xl font-black text-white"
                             style="background: linear-gradient(135deg, rgb(99, 102, 241), rgb(168, 85, 247));">
                            1
                        </div>
                        <h4 class="font-bold text-white text-base">Publiez votre besoin</h4>
                        <p class="text-xs leading-relaxed" style="color: rgb(148, 163, 184);">
                            Formulez votre demande en quelques clics avec la matière, le niveau et le budget envisagé.
                        </p>
                    </div>

                    <div class="space-y-3 text-center">
                        <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center text-xl font-black text-white"
                             style="background: linear-gradient(135deg, rgb(168, 85, 247), rgb(236, 72, 153));">
                            2
                        </div>
                        <h4 class="font-bold text-white text-base">Recevez des propositions</h4>
                        <p class="text-xs leading-relaxed" style="color: rgb(148, 163, 184);">
                            Les tuteurs qualifiés vous envoient leur tarif et leur approche pédagogique détaillée.
                        </p>
                    </div>

                    <div class="space-y-3 text-center">
                        <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center text-xl font-black text-white"
                             style="background: linear-gradient(135deg, rgb(34, 197, 94), rgb(59, 130, 246));">
                            3
                        </div>
                        <h4 class="font-bold text-white text-base">Apprenez & Évaluez</h4>
                        <p class="text-xs leading-relaxed" style="color: rgb(148, 163, 184);">
                            Acceptez l'offre qui vous convient, échangez directement et notez le tuteur une fois les cours finis.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- CALL TO ACTION --}}
        <section class="py-16 px-6">
            <div class="max-w-4xl mx-auto rounded-3xl p-10 lg:p-14 text-center space-y-6 relative overflow-hidden"
                 style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(168, 85, 247, 0.15)); border: 1px solid rgba(99, 102, 241, 0.3); box-shadow: 0 20px 40px rgba(0,0,0,0.4);">
                <h3 class="text-3xl font-black text-white tracking-tight">Prêt à démarrer l'aventure ?</h3>
                <p class="text-sm max-w-lg mx-auto" style="color: rgb(203, 213, 225);">
                    Rejoignez dès aujourd'hui la communauté TutorLink. Inscription gratuite et immédiate.
                </p>
                <div class="flex items-center justify-center gap-4 pt-2">
                    <a href="{{ route('register') }}" class="btn-primary px-8 py-3.5 text-base font-bold shadow-lg" style="text-decoration: none;">
                        Créer mon compte
                    </a>
                </div>
            </div>
        </section>

        {{-- FOOTER --}}
        <footer class="mt-auto py-8 px-6 text-center text-xs" style="border-top: 1px solid rgba(255, 255, 255, 0.05); color: rgb(148, 163, 184);">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🎓</span>
                    <span class="font-bold text-white">TutorLink</span>
                    <span>— Tous droits réservés &copy; {{ date('Y') }}</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('demandes.index') }}" class="hover:text-white transition-colors" style="text-decoration:none;">Demandes</a>
                    <a href="{{ route('login') }}" class="hover:text-white transition-colors" style="text-decoration:none;">Connexion</a>
                    <a href="{{ route('register') }}" class="hover:text-white transition-colors" style="text-decoration:none;">Inscription</a>
                </div>
            </div>
        </footer>

    </div>
</body>
</html>
