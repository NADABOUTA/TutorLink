<?php

namespace App\Events;

use App\Models\Offre;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OffreAcceptee
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Offre $offre;

    /**
     * Create a new event instance.
     */
    public function __construct(Offre $offre)
    {
        $this->offre = $offre;
    }
}
