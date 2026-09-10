<x-guest-layout>
    <x-slot name="title">Connexion</x-slot>

    <div class="auth-card animate-slide-up">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center justify-center w-12 h-12 rounded-xl text-white font-extrabold text-base mb-4 shadow-sm transition-transform hover:scale-105 no-underline" style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);">
                TL
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Connexion à votre compte</h1>
            <p class="text-xs mt-1.5 text-slate-500 font-medium">Accédez à votre espace pédagogique TutorLink Maroc</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div class="form-group mb-0">
                <label for="email" class="form-label">Adresse email professionnelle ou personnelle</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="form-input" placeholder="nom@exemple.com">
                @error('email')
                    <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group mb-0">
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="form-label mb-0">Mot de passe</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required class="form-input" placeholder="••••••••">
                @error('password')
                    <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2 pt-1 pb-2">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                <label for="remember_me" class="text-sm cursor-pointer text-slate-600 font-medium select-none">Se souvenir de cet appareil</label>
            </div>

            <button type="submit" class="btn-primary w-full py-2.5 justify-center text-sm shadow-sm font-semibold">
                Se connecter
            </button>
        </form>

        @if (Route::has('register'))
            <p class="text-center text-sm mt-6 text-slate-500">
                Vous n'avez pas encore de compte ?
                <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                    S'inscrire
                </a>
            </p>
        @endif
    </div>
</x-guest-layout>
