<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Implémente MustVerifyEmail pour la vérification d'email
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role_id',
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    /**
     * Récupère le rôle de l'utilisateur.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Récupère le profil "couple" de l'utilisateur, si existant.
     */
    public function mariee()
    {
        return $this->hasOne(Mariee::class);
    }

    /**
     * Récupère le profil "traiteur" de l'utilisateur, si existant.
     */
    public function Traiteur()
    {
        return $this->hasOne(Traiteur::class);
    }

    /**
     * Vérifie si l'utilisateur est un couple.
     */
    public function isMariee()
    {
        return $this->role->name === 'mariee';
    }

    /**
     * Vérifie si l'utilisateur est un traiteur.
     */
    public function isTraiteur()
    {
        return $this->role->name === 'traiteur';
    }

    /**
     * Vérifie si l'utilisateur est un administrateur.
     */
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }
}
