<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Rôle selection -->
        <div class="mb-4">
            <x-input-label :value="__('Je rejoins TutorLink en tant que :')" class="font-semibold text-gray-800 dark:text-gray-200" />
            
            <div class="grid grid-cols-2 gap-4 mt-2">
                <label class="cursor-pointer border-2 rounded-xl p-4 flex flex-col items-center justify-center transition-all duration-200 hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/30 border-gray-200 dark:border-gray-700">
                    <input type="radio" name="role" value="apprenant" class="sr-only" {{ old('role', 'apprenant') === 'apprenant' ? 'checked' : '' }} required>
                    <div class="text-2xl mb-1">🎓</div>
                    <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Apprenant</span>
                    <span class="text-xs text-gray-500 text-center mt-1">Je cherche un tuteur</span>
                </label>

                <label class="cursor-pointer border-2 rounded-xl p-4 flex flex-col items-center justify-center transition-all duration-200 hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/30 border-gray-200 dark:border-gray-700">
                    <input type="radio" name="role" value="tuteur" class="sr-only" {{ old('role') === 'tuteur' ? 'checked' : '' }} required>
                    <div class="text-2xl mb-1">👨‍🏫</div>
                    <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Tuteur</span>
                    <span class="text-xs text-gray-500 text-center mt-1">Je donne des cours</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nom complet')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Adresse Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Téléphone -->
        <div class="mt-4">
            <x-input-label for="telephone" :value="__('Téléphone (optionnel)')" />
            <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" :value="old('telephone')" placeholder="06 12 34 56 78" autocomplete="tel" />
            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Déjà inscrit ?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Créer mon compte') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
