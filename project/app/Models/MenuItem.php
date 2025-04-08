<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'service_id',
        'name',
        'description',
        'price',
        'photo',
        'category',
    ];

    /**
     * Récupère le service auquel cet item appartient.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
