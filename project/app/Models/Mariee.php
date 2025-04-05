<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mariee extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'groom_name',
        'bride_name',
        'phone_number',
        'city',
        'budget',
        'wedding_date',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'wedding_date' => 'date',
        'budget' => 'decimal:2',
    ];

    /**
     * Récupère l'utilisateur associé au couple.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Vous pourrez ajouter d'autres relations ici plus tard
    // Par exemple: réservations, favoris, commentaires, etc.
}
