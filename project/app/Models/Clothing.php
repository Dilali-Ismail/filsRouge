<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clothing extends Model
{
    use HasFactory;


    protected $table = 'clothing_items';

    protected $fillable = [
        'service_id',
        'name',
        'description',
        'price',
        'photo',
        'category',
        'style',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
