<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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


    public function services()
    {
    return $this->hasMany(Service::class);
    }

}
