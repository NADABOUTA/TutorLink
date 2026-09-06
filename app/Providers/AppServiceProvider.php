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
    }
}
