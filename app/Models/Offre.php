<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'tuteur_id',
        'message',
        'tarif_propose',
        'statut',
        'coordonnees_visibles',
    ];

    protected function casts(): array
    {
        return [
            'tarif_propose' => 'decimal:2',
            'coordonnees_visibles' => 'boolean',
        ];
    }

    /**
     * La demande concernée par cette offre.
     */
    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class, 'demande_id');
    }

    /**
     * Le tuteur ayant proposé cette offre.
     */
    public function tuteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tuteur_id');
    }
}
