<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makeup extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'description',
        'price',
        'photo',
        'experience'
    ];

   
    public function service()
    {
        return $this->belongsTo(Service::class);
    }


    public function portfolioItems()
    {
        return $this->hasMany(MakeupPortfolioItem::class);
    }
}
