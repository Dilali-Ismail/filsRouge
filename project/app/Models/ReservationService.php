<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReservationService extends Model
{
    use HasFactory;

    protected $fillable = ['reservation_id','service_id','service_item_id','service_item_type','price','quantity'];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }

    //recuperer un item de service comme negafa , decoration

    public function serviceItem():MorphTo{

         return $this->morphTo();
    }

    public function SubtotalQuantity(){
        return $this->price * $this->quantity ;
    }

}
