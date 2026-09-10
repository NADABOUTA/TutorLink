<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">Mon Profil</h1>
            <p class="page-subtitle">Gérez vos informations personnelles et la sécurité de votre compte.</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="card">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card border-rose-200">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
