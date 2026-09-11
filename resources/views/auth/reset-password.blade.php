<x-guest-layout>
    <x-slot name="title">Nouveau mot de passe</x-slot>

    <div class="auth-card">
        {{-- Emblem Header --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-glow-amber mb-4 no-underline group hover:scale-105 transition-transform duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </a>
            <div class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold tracking-widest uppercase bg-amber-500/10 text-amber-400 border border-amber-500/20 mb-3">
                Sécurité renforcée
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight font-display">Nouveau mot de passe</h1>
            <p class="text-xs mt-1 text-slate-400">Choisissez un nouveau mot de passe pour sécuriser votre compte.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group mb-0">
                <label for="email" class="form-label">Adresse email <span class="text-amber-400">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                           class="form-input pl-10" placeholder="nom@exemple.com">
                </div>
                @error('email')
                    <p class="text-xs mt-1.5 text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group mb-0">
                <label for="password" class="form-label">Nouveau mot de passe <span class="text-amber-400">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </span>
                    <input id="password" type="password" name="password" required
                           class="form-input pl-10" placeholder="••••••••">
                </div>
                @error('password')
                    <p class="text-xs mt-1.5 text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group mb-0">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-amber-400">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="form-input pl-10" placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3 justify-center text-xs tracking-wider uppercase font-bold mt-2 shadow-glow-amber">
                <span>Mettre à jour le mot de passe</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>
</x-guest-layout>
