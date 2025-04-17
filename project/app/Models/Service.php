<?php

namespace App\Models;

use App\Models\Clothing;
use App\Models\Traiteur;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;


    protected $fillable = [
        'traiteur_id',
        'category_id',
        'title',
        'description',
    ];


    public function traiteur()
    {
        return $this->belongsTo(Traiteur::class);
    }


    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }


    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function clothing()
    {
        return $this->hasMany(Clothing::class);
    }
    public function negafas()
    {
        return $this->hasMany(Negafa::class);
    }
    public function makeups()
    {
        return $this->hasMany(Makeup::class);
    }

    public function photographers()
    {
        return $this->hasMany(Photographer::class);
    }

    public function amariyas()
    {
        return $this->hasMany(Amariya::class);
    }
    public function decorations()
    {
        return $this->hasMany(Decoration::class);
    }
    public function salles()
    {
        return $this->hasMany(Salle::class);
    }
    public function animations()
    {
        return $this->hasMany(Animation::class);
    }
    public function reservationServices()
    {
        return $this->hasMany(ReservationService::class);
    }
    
}
