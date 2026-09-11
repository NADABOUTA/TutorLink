<section>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-amber-400 bg-amber-500/10 border border-amber-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
            <h2 class="text-base font-black text-white font-display">Informations du profil</h2>
            <p class="text-xs text-slate-400">Mettez à jour les informations de votre compte et votre adresse email.</p>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="form-group mb-0">
            <label for="name" class="form-label">Nom complet <span class="text-amber-400">*</span></label>
            <input id="name" name="name" type="text" class="form-input" :value="old('name', $user->name)" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group mb-0">
            <label for="email" class="form-label">Adresse email <span class="text-amber-400">*</span></label>
            <input id="email" name="email" type="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-xs text-amber-300">
                    Votre adresse email n'est pas vérifiée.
                    <button form="send-verification" class="underline font-bold text-amber-400 hover:text-amber-300 cursor-pointer">
                        Cliquez ici pour renvoyer l'email de vérification.
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 font-medium text-emerald-400">Un nouveau lien de vérification a été envoyé à votre adresse email.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-800">
            <button type="submit" class="btn-primary text-xs py-2.5 px-6">Enregistrer</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-xs text-emerald-400 font-bold">Modifications enregistrées.</p>
            @endif
        </div>
    </form>
</section>