<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraiteurAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'traiteur_id',
        'date',
        'is_available'
    ];

    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean'
    ];

    public function traiteur(){
        return $this->belongsTo(Traiteur::class);
    }
}
