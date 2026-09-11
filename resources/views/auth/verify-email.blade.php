<x-guest-layout>
    <x-slot name="title">Vérification de l'email</x-slot>

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
                Activation
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight font-display">Vérifiez votre email</h1>
            <p class="text-xs mt-1 text-slate-400">Merci pour votre inscription ! Un lien de validation a été envoyé à votre adresse email.</p>
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 text-xs mb-5 flex items-center gap-2.5">
                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span>Un nouveau lien de vérification vous a été envoyé.</span>
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary w-full py-3 justify-center text-xs tracking-wider uppercase font-bold shadow-glow-amber">
                    Renvoyer l'email de vérification
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-xs font-semibold text-slate-400 hover:text-white transition cursor-pointer">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
