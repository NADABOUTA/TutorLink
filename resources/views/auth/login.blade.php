<x-guest-layout>
    <x-slot name="title">Connexion</x-slot>

    <div class="auth-card animate-slide-up">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl text-white text-xl font-bold mb-4" style="background: linear-gradient(135deg, rgb(99,102,241), rgb(168,85,247));">
                TL
            </div>
            <h1 class="text-2xl font-bold text-white">Bon retour ! 👋</h1>
            <p class="text-sm mt-1" style="color: rgb(148,163,184);">Connectez-vous à votre espace TutorLink</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Adresse email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="form-input" placeholder="vous@exemple.com">
                @error('email')
                    <p class="text-xs mt-1.5" style="color: rgb(239,68,68);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="form-label" style="margin-bottom:0;">Mot de passe</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs hover:underline" style="color: rgb(99,102,241);">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required class="form-input" placeholder="••••••••">
                @error('password')
                    <p class="text-xs mt-1.5" style="color: rgb(239,68,68);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2 mb-6">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded cursor-pointer" style="border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.05) !important;">
                <label for="remember_me" class="text-sm cursor-pointer" style="color: rgb(148,163,184);">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn-primary w-full py-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Se connecter
            </button>
        </form>

        @if (Route::has('register'))
            <p class="text-center text-sm mt-6" style="color: rgb(148,163,184);">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color: rgb(99,102,241);">
                    Créer un compte
                </a>
            </p>
        @endif
    </div>
</x-guest-layout>
