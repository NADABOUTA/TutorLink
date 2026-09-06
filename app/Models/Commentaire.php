<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commentaire extends Model
{
    use HasFactory;

    protected $table = 'commentaires';

    protected $fillable = [
        'demande_id',
        'user_id',
        'offre_id',
        'contenu',
    ];

    /**
     * La demande associée au commentaire.
     */
    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class, 'demande_id');
    }

    /**
     * L'auteur du commentaire (apprenant, tuteur ou admin).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * L'offre ciblée par ce commentaire (optionnel).
     */
    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class, 'offre_id');
    }
}
