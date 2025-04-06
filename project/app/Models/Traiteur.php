<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traiteur extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'manager_name',
        'registration_number',
        'phone_number',
        'city',
        'logo',
        'is_verified',
    ];


    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
