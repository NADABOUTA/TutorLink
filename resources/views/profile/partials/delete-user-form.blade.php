<section class="space-y-5">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-rose-400 bg-rose-500/10 border border-rose-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div>
            <h2 class="text-base font-black text-white font-display">Supprimer le compte</h2>
            <p class="text-xs text-slate-400">Une fois le compte supprimé, toutes ses données seront définitivement effacées.</p>
        </div>
    </div>

    <p class="text-xs text-slate-400 leading-relaxed">
        La suppression du compte est irréversible. Avant de continuer, pensez à sauvegarder les informations que vous souhaitez conserver.
    </p>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="text-xs py-2.5 px-6 font-bold"
    >Supprimer le compte</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-[#101726] border border-slate-800 rounded-3xl">
            @csrf
            @method('delete')

            <h2 class="text-lg font-black text-white font-display">Êtes-vous sûr de vouloir supprimer votre compte ?</h2>

            <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                La suppression est irréversible. Veuillez saisir votre mot de passe pour confirmer la suppression définitive de votre compte.
            </p>

            <div class="mt-6">
                <label for="password" class="form-label sr-only">Mot de passe</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-input w-full"
                    placeholder="Saisissez votre mot de passe pour confirmer"
                />

                @error('password', 'userDeletion')
                    <p class="text-xs mt-1 text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="text-xs py-2.5 px-5">
                    Annuler
                </x-secondary-button>

                <x-danger-button class="text-xs py-2.5 px-6 font-bold">
                    Supprimer définitivement
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>