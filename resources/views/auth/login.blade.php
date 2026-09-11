<x-guest-layout>
    <x-slot name="title">Connexion</x-slot>

    <div class="auth-card max-w-md w-full" x-data="{ showPassword: false }">
        <!-- Top Portal Emblem -->
        <div class="flex flex-col items-center text-center mb-7">
            <div class="relative mb-4">
                <div class="w-14 h-14 rounded-2xl bg-[#101726] border border-amber-500/30 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                    </svg>
                </div>
            </div>

            <span class="text-[11px] font-bold tracking-widest text-amber-500 uppercase mb-1.5">TutorLink Portal</span>
            <h1 class="text-2xl font-black text-white tracking-tight font-display flex items-center gap-2">
                Bon retour ! <span class="animate-bounce inline-block">👋</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Connectez-vous à votre espace TutorLink</p>
        </div>

        <x-auth-session-status class="mb-5" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div class="form-group mb-0">
                <label for="email" class="form-label">Adresse Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="form-input pl-10" placeholder="admin@tutorlink.com">
                </div>
                @error('email')
                    <p class="text-xs mt-1.5 text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group mb-0">
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="form-label mb-0">Mot de passe</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-amber-400 hover:text-amber-300 transition">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                           class="form-input pl-10 pr-10" placeholder="••••••••">
                    <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300">
                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs mt-1.5 text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember me -->
            <div class="flex items-center gap-2 pt-1">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-slate-700 bg-[#0B101B] text-amber-500 focus:ring-amber-500/20 cursor-pointer">
                <label for="remember_me" class="text-xs cursor-pointer text-slate-400 select-none">Se souvenir de moi</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm tracking-wide mt-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                <span>Se connecter</span>
            </button>
        </form>

        @if (Route::has('register'))
            <p class="text-center text-xs mt-6 text-slate-400">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="font-bold text-amber-400 hover:text-amber-300 ml-1 transition">
                    Créer un compte
                </a>
            </p>
        @endif
    </div>
</x-guest-layout>
