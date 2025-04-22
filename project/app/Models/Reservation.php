<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'mariee_id',
        'traiteur_id',
        'event_date',
        'nombre_invites',
        'nombre_tables',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'event_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    public function mariee(){
        return $this->belongsTo(Mariee::class);
    }

    public function traiteur(){
        return $this->belongsTo(Traiteur::class);
    }

    public function services(){
        return $this->hasMany(Service::class);
    }

    public function payments(){
       return $this->hasMany(Payment::class);
    }
    public function isPayed(){
        return $this->payments()->where('status','completed')->sum('amount') >= $this->total_amount ;
    }


}
