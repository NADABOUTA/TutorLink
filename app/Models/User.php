<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'telephone',
        'matiere',
        'bio',
        'tarif_horaire',
        'is_active',
    ];

    /**
     * Default model attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role' => 'apprenant',
        'is_active' => true,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tarif_horaire' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Demandes publiées par l'apprenant.
     */
    public function demandes(): HasMany
    {
        return $this->hasMany(Demande::class, 'apprenant_id');
    }

    /**
     * Offres formulées par le tuteur.
     */
    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class, 'tuteur_id');
    }

    /**
     * Avis reçus par le tuteur.
     */
    public function avisRecus(): HasMany
    {
        return $this->hasMany(Avis::class, 'tuteur_id');
    }

    /**
     * Avis déposés par l'apprenant.
     */
    public function avisDonnes(): HasMany
    {
        return $this->hasMany(Avis::class, 'apprenant_id');
    }

    /**
     * Note moyenne calculée pour le profil du tuteur (évite N+1 si relation eager-loaded).
     */
    public function getNoteMoyenneAttribute(): ?float
    {
        if ($this->relationLoaded('avisRecus')) {
            $avg = $this->avisRecus->avg('note');
            return $avg !== null ? round((float) $avg, 1) : null;
        }

        $avg = $this->avisRecus()->avg('note');
        return $avg !== null ? round((float) $avg, 1) : null;
    }

    /**
     * Vérifie si l'utilisateur possède un rôle particulier.
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles, true);
        }

        return $this->role === $roles;
    }

    /**
     * Vérifie si l'utilisateur est un administrateur.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un tuteur.
     */
    public function isTuteur(): bool
    {
        return $this->role === 'tuteur';
    }

    /**
     * Vérifie si l'utilisateur est un apprenant.
     */
    public function isApprenant(): bool
    {
        return $this->role === 'apprenant';
    }

    /**
     * Assigne un rôle à l'utilisateur (rétrocompatibilité fluide).
     */
    public function addRole(mixed $role): self
    {
        $roleName = is_object($role) && isset($role->name) ? $role->name : (string) $role;
        $this->role = $roleName;
        $this->save();

        return $this;
    }
}
