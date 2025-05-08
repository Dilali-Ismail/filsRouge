<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotographerPortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'photographer_id',
        'type',
        'file_path',
        'title',
        'description'
    ];

    
    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }
}
