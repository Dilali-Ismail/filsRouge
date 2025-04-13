<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegafaPortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'negafa_id',
        'type',
        'file_path',
        'title',
        'description'
    ];

    public function negafa()
    {
        return $this->belongsTo(Negafa::class);
    }
}
