<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'service_id',
        'name',
        'description',
        'price',
        'photo',
        'category',
    ];

   
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
