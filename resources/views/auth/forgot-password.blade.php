<x-guest-layout>
    <x-slot name="title">Mot de passe oublié</x-slot>

    <div class="auth-card max-w-md w-full">
        <div class="flex flex-col items-center text-center mb-7">
            <div class="relative mb-4">
                <div class="w-14 h-14 rounded-2xl bg-[#101726] border border-amber-500/30 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
            </div>

            <span class="text-[11px] font-bold tracking-widest text-amber-500 uppercase mb-1.5">Récupération de compte</span>
            <h1 class="text-2xl font-black text-white tracking-tight font-display">Mot de passe oublié</h1>
            <p class="text-xs text-slate-400 mt-1 max-w-xs">Entrez votre email pour recevoir les instructions de réinitialisation.</p>
        </div>

        @if (session('status'))
            <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div class="form-group mb-0">
                <label for="email" class="form-label">Adresse email</label>
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

            <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm tracking-wide mt-2">
                Envoyer le lien de réinitialisation
            </button>
        </form>

        <p class="text-center text-xs mt-6 text-slate-400">
            <a href="{{ route('login') }}" class="font-bold text-amber-400 hover:text-amber-300 transition">
                &larr; Retour à la connexion
            </a>
        </p>
    </div>
</x-guest-layout>
