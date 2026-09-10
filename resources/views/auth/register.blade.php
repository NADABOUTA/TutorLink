<x-guest-layout>
    <x-slot name="title">Inscription</x-slot>

    <div class="auth-card animate-slide-up" style="max-width: 520px;">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center justify-center w-12 h-12 rounded-xl text-white font-extrabold text-base mb-4 shadow-sm transition-transform hover:scale-105 no-underline" style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);">
                TL
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Créer un compte TutorLink</h1>
            <p class="text-xs mt-1.5 text-slate-500 font-medium">Rejoignez le premier réseau académique de soutien au Maroc</p>
        </div>

        <form method="POST" action="{{ route('register') }}" x-data="{ role: '{{ old('role', 'apprenant') }}' }" class="space-y-4">
            @csrf

            <!-- Role Selector -->
            <div>
                <label class="form-label mb-2">Type de compte</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="role-card" :class="{ 'selected': role === 'apprenant' }" @click="role = 'apprenant'">
                        <input type="radio" name="role" value="apprenant" class="sr-only" x-model="role" required>
                        <div class="w-9 h-9 mx-auto mb-2 rounded-lg flex items-center justify-center text-blue-700 bg-blue-50 border border-blue-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <span class="role-title block text-sm">Apprenant</span>
                        <span class="role-desc">Élève ou étudiant</span>
                    </label>
                    <label class="role-card" :class="{ 'selected': role === 'tuteur' }" @click="role = 'tuteur'">
                        <input type="radio" name="role" value="tuteur" class="sr-only" x-model="role" required>
                        <div class="w-9 h-9 mx-auto mb-2 rounded-lg flex items-center justify-center text-slate-700 bg-slate-50 border border-slate-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <span class="role-title block text-sm">Tuteur</span>
                        <span class="role-desc">Enseignant qualifié</span>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div class="form-group mb-0">
                <label for="name" class="form-label">Nom et prénom</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="form-input" placeholder="Ex: Karim Alami">
                @error('name')
                    <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group mb-0">
                <label for="email" class="form-label">Adresse email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="form-input" placeholder="karim@exemple.com">
                @error('email')
                    <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Téléphone -->
            <div class="form-group mb-0">
                <label for="telephone" class="form-label flex items-center justify-between">
                    <span>Numéro de téléphone</span>
                    <span class="text-xs font-normal text-slate-400">Pour la mise en relation</span>
                </label>
                <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}"
                       class="form-input" placeholder="06 12 34 56 78">
                @error('telephone')
                    <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-group mb-0">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input id="password" type="password" name="password" required
                           class="form-input" placeholder="••••••••">
                    @error('password')
                        <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group mb-0">
                    <label for="password_confirmation" class="form-label">Confirmation</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="form-input" placeholder="••••••••">
                    @error('password_confirmation')
                        <p class="text-xs mt-1.5 text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-2.5 justify-center text-sm shadow-sm font-semibold mt-2">
                Créer mon compte
            </button>
        </form>

        <p class="text-center text-sm mt-6 text-slate-500">
            Déjà inscrit sur la plateforme ?
            <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                Se connecter
            </a>
        </p>
    </div>
</x-guest-layout>
