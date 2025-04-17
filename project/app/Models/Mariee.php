<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mariee extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'groom_name',
        'bride_name',
        'phone_number',
        'city',
        'budget',
        'wedding_date',
    ];

    protected $casts = [
        'wedding_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

}
