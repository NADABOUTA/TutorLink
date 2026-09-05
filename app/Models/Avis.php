<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avis extends Model
{
    use HasFactory;

    protected $table = 'avis';

    protected $fillable = [
        'demande_id',
        'apprenant_id',
        'tuteur_id',
        'note',
        'commentaire',
    ];

    protected function casts(): array
    {
        return [
            'note' => 'integer',
        ];
    }

    /**
     * La demande associée à l'avis.
     */
    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class, 'demande_id');
    }

    /**
     * L'apprenant ayant laissé l'avis.
     */
    public function apprenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'apprenant_id');
    }

    /**
     * Le tuteur ayant reçu l'avis.
     */
    public function tuteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tuteur_id');
    }
}
