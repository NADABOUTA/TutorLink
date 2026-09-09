<?php

namespace App\Providers;

use App\Events\OffreAcceptee;
use App\Listeners\EnvoyerNotificationEtCoordonnees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Interdire le lazy loading en développement pour traquer systématiquement les requêtes N+1
        Model::preventLazyLoading(! $this->app->isProduction());

        Event::listen(
            OffreAcceptee::class,
            EnvoyerNotificationEtCoordonnees::class
        );

        // Définition des Gates d'autorisation natives
        \Illuminate\Support\Facades\Gate::define('admin', fn(\App\Models\User $user) => $user->isAdmin());
        \Illuminate\Support\Facades\Gate::define('tuteur', fn(\App\Models\User $user) => $user->isTuteur());
        \Illuminate\Support\Facades\Gate::define('apprenant', fn(\App\Models\User $user) => $user->isApprenant());
        \Illuminate\Support\Facades\Gate::define('manage-users', fn(\App\Models\User $user) => $user->isAdmin());
        \Illuminate\Support\Facades\Gate::define('moderate-demandes', fn(\App\Models\User $user) => $user->isAdmin());
    }
}
