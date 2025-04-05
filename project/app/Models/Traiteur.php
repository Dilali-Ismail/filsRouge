<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traiteur extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'manager_name',
        'registration_number',
        'phone_number',
        'city',
        'logo',
        'is_verified',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * Récupère l'utilisateur associé au traiteur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Vous pourrez ajouter d'autres relations ici plus tard
    // Par exemple: services, disponibilités, réservations, etc.
}
