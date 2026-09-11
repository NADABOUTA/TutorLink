<section>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-amber-400 bg-amber-500/10 border border-amber-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
        </div>
        <div>
            <h2 class="text-base font-black text-white font-display">Mettre à jour le mot de passe</h2>
            <p class="text-xs text-slate-400">Assurez-vous que votre compte utilise un mot de passe robuste et confidentiel.</p>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div class="form-group mb-0">
            <label for="update_password_current_password" class="form-label">Mot de passe actuel</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-input" autocomplete="current-password" />
            @error('current_password', 'updatePassword')
                <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group mb-0">
            <label for="update_password_password" class="form-label">Nouveau mot de passe</label>
            <input id="update_password_password" name="password" type="password" class="form-input" autocomplete="new-password" />
            @error('password', 'updatePassword')
                <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group mb-0">
            <label for="update_password_password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-input" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
                <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-800">
            <button type="submit" class="btn-primary text-xs py-2.5 px-6">Enregistrer</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-xs text-emerald-400 font-bold">Mot de passe mis à jour.</p>
            @endif
        </div>
    </form>
</section>