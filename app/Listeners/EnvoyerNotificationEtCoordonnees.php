<?php

namespace App\Listeners;

use App\Events\OffreAcceptee;
use App\Jobs\NotifierUtilisateurs;

class EnvoyerNotificationEtCoordonnees
{
    /**
     * Handle the event.
     */
    public function handle(OffreAcceptee $event): void
    {
        // Dispatche le job dans la file d'attente (driver database)
        NotifierUtilisateurs::dispatch($event->offre);
    }
}
