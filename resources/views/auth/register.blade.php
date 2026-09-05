<x-guest-layout>
    <x-slot name="title">Inscription</x-slot>

    <div class="auth-card animate-slide-up" style="max-width: 480px;">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl text-white text-xl font-bold mb-4" style="background: linear-gradient(135deg, rgb(99,102,241), rgb(168,85,247));">
                TL
            </div>
            <h1 class="text-2xl font-bold text-white">Rejoindre TutorLink ✨</h1>
            <p class="text-sm mt-1" style="color: rgb(148,163,184);">Créez votre compte et commencez aujourd'hui</p>
        </div>

        <form method="POST" action="{{ route('register') }}" x-data="{ role: '{{ old('role', 'apprenant') }}' }">
            @csrf

            <!-- Role Selector -->
            <div class="form-group">
                <label class="form-label">Je rejoins en tant que</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="role-card" :class="{ 'selected': role === 'apprenant' }" @click="role = 'apprenant'">
                        <input type="radio" name="role" value="apprenant" class="sr-only" x-model="role" required>
                        <span class="role-emoji">🎓</span>
                        <span class="role-title">Apprenant</span>
                        <span class="role-desc">Je cherche un tuteur</span>
                    </label>
                    <label class="role-card" :class="{ 'selected': role === 'tuteur' }" @click="role = 'tuteur'">
                        <input type="radio" name="role" value="tuteur" class="sr-only" x-model="role" required>
                        <span class="role-emoji">👨‍🏫</span>
                        <span class="role-title">Tuteur</span>
                        <span class="role-desc">Je donne des cours</span>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs mt-1.5" style="color: rgb(239,68,68);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="form-input" placeholder="Jean Dupont">
                @error('name')
                    <p class="text-xs mt-1.5" style="color: rgb(239,68,68);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Adresse email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="form-input" placeholder="vous@exemple.com">
                @error('email')
                    <p class="text-xs mt-1.5" style="color: rgb(239,68,68);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Téléphone -->
            <div class="form-group">
                <label for="telephone" class="form-label">Téléphone <span style="color:rgba(148,163,184,0.5)">(optionnel)</span></label>
                <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}"
                       class="form-input" placeholder="06 12 34 56 78">
                @error('telephone')
                    <p class="text-xs mt-1.5" style="color: rgb(239,68,68);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input id="password" type="password" name="password" required
                       class="form-input" placeholder="••••••••">
                @error('password')
                    <p class="text-xs mt-1.5" style="color: rgb(239,68,68);">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="form-input" placeholder="••••••••">
                @error('password_confirmation')
                    <p class="text-xs mt-1.5" style="color: rgb(239,68,68);">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary w-full py-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Créer mon compte
            </button>
        </form>

        <p class="text-center text-sm mt-6" style="color: rgb(148,163,184);">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color: rgb(99,102,241);">
                Se connecter
            </a>
        </p>
    </div>
</x-guest-layout>
