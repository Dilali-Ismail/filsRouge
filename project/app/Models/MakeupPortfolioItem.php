<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakeupPortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'makeup_id',
        'type',
        'file_path',
        'title',
        'description'
    ];

 
    public function makeup()
    {
        return $this->belongsTo(Makeup::class);
    }
}
