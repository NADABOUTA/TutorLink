<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'apprenant_id',
        'matiere',
        'niveau',
        'description',
        'budget',
        'statut',
        'motif_refus',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
        ];
    }

    /**
     * L'apprenant qui a créé la demande.
     */
    public function apprenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'apprenant_id');
    }

    /**
     * Les offres faites par les tuteurs pour cette demande.
     */
    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class, 'demande_id');
    }

    /**
     * L'avis déposé par l'apprenant pour cette demande.
     */
    public function avis(): HasOne
    {
        return $this->hasOne(Avis::class, 'demande_id');
    }
}
