<?php

namespace App\Jobs;

use App\Models\Offre;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifierUtilisateurs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Offre $offre;

    /**
     * Create a new job instance.
     */
    public function __construct(Offre $offre)
    {
        $this->offre = $offre;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Débloquer la visibilité des coordonnées pour les deux parties
        $this->offre->update([
            'coordonnees_visibles' => true,
        ]);

        // Eager load pour récupérer les infos des deux utilisateurs
        $this->offre->loadMissing(['demande.apprenant', 'tuteur']);

        $apprenant = $this->offre->demande->apprenant;
        $tuteur = $this->offre->tuteur;

        // 2. Notification / Log de confirmation pour l'apprenant et le tuteur
        Log::info("Notification TutorLink : L'offre #{$this->offre->id} a été acceptée.", [
            'demande_id' => $this->offre->demande_id,
            'matiere' => $this->offre->demande->matiere,
            'apprenant' => [
                'id' => $apprenant->id,
                'name' => $apprenant->name,
                'email' => $apprenant->email,
                'telephone' => $apprenant->telephone,
            ],
            'tuteur' => [
                'id' => $tuteur->id,
                'name' => $tuteur->name,
                'email' => $tuteur->email,
                'telephone' => $tuteur->telephone,
                'tarif' => $this->offre->tarif_propose,
            ],
            'coordonnees_visibles' => true,
        ]);
    }
}
