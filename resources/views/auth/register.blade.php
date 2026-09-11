<x-guest-layout>
    <x-slot name="title">Inscription</x-slot>

    <div class="auth-card max-w-lg w-full" x-data="{ role: '{{ request('role', old('role', 'apprenant')) }}' }">
        <!-- Top Portal Emblem -->
        <div class="flex flex-col items-center text-center mb-6">
            <div class="relative mb-3">
                <div class="w-12 h-12 rounded-2xl bg-[#101726] border border-amber-500/30 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-2xl font-black text-white tracking-tight font-display flex items-center gap-2">
                Rejoindre TutorLink <span class="text-amber-400">✨</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Créez votre compte et commencez aujourd'hui</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Role Selector -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Je rejoins en tant que</label>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Apprenant Option -->
                    <label class="relative rounded-2xl p-4 cursor-pointer transition-all duration-200 block border text-center"
                           :class="role === 'apprenant'
                                ? 'border-amber-500 bg-amber-500/10 shadow-lg shadow-amber-500/10'
                                : 'border-slate-800 bg-[#0B101B] hover:border-slate-700'"
                           @click="role = 'apprenant'">
                        <input type="radio" name="role" value="apprenant" class="sr-only" x-model="role" required>
                        <!-- Top-Right Indicator Dot -->
                        <div class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full"
                             :class="role === 'apprenant' ? 'bg-amber-400 shadow-glow-sm' : 'bg-transparent'"></div>
                        
                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl flex items-center justify-center transition-colors"
                             :class="role === 'apprenant' ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-800/60 text-slate-400'">
                            <span class="text-xl">🎓</span>
                        </div>
                        <span class="block font-bold text-sm text-white">Apprenant</span>
                        <span class="block text-[11px] text-slate-400 mt-0.5">Je cherche un tuteur</span>
                    </label>

                    <!-- Tuteur Option -->
                    <label class="relative rounded-2xl p-4 cursor-pointer transition-all duration-200 block border text-center"
                           :class="role === 'tuteur'
                                ? 'border-amber-500 bg-amber-500/10 shadow-lg shadow-amber-500/10'
                                : 'border-slate-800 bg-[#0B101B] hover:border-slate-700'"
                           @click="role = 'tuteur'">
                        <input type="radio" name="role" value="tuteur" class="sr-only" x-model="role" required>
                        <!-- Top-Right Indicator Dot -->
                        <div class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full"
                             :class="role === 'tuteur' ? 'bg-amber-400 shadow-glow-sm' : 'bg-transparent'"></div>

                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl flex items-center justify-center transition-colors"
                             :class="role === 'tuteur' ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-800/60 text-slate-400'">
                            <span class="text-xl">👨‍🏫</span>
                        </div>
                        <span class="block font-bold text-sm text-white">Tuteur</span>
                        <span class="block text-[11px] text-slate-400 mt-0.5">Je donne des cours</span>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs mt-1.5 text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div class="form-group mb-0">
                <label for="name" class="form-label">Nom complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="form-input" placeholder="Jean Dupont">
                @error('name')
                    <p class="text-xs mt-1.5 text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group mb-0">
                <label for="email" class="form-label">Adresse email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="form-input" placeholder="admin@tutorlink.com">
                @error('email')
                    <p class="text-xs mt-1.5 text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="form-group mb-0">
                <div class="flex items-center justify-between mb-1">
                    <label for="telephone" class="form-label mb-0">Téléphone</label>
                    <span class="text-[11px] text-slate-500 italic">optionnel</span>
                </div>
                <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}"
                       class="form-input" placeholder="0612345678">
                @error('telephone')
                    <p class="text-xs mt-1.5 text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group mb-0">
                <label for="password" class="form-label">Mot de passe</label>
                <input id="password" type="password" name="password" required
                       class="form-input" placeholder="••••••••••••••••">
                @error('password')
                    <p class="text-xs mt-1.5 text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="form-group mb-0">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="form-input" placeholder="••••••••">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm tracking-wide mt-3 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>Créer mon compte</span>
            </button>
        </form>

        <p class="text-center text-xs mt-6 text-slate-400">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="font-bold text-amber-400 hover:text-amber-300 ml-1 transition">
                Se connecter
            </a>
        </p>
    </div>
</x-guest-layout>
