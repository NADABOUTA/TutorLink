<x-app-layout>
    <x-slot name="header">
        <div class="w-full">
            <h1 class="page-title text-2xl font-black text-white font-display">Mon Profil</h1>
            <p class="page-subtitle text-xs text-slate-400 mt-0.5">Gérez vos informations personnelles et la sécurité de votre compte.</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="card p-8 rounded-3xl shadow-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card p-8 rounded-3xl shadow-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card p-8 rounded-3xl shadow-xl border-rose-500/25 bg-rose-500/[0.02]">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
