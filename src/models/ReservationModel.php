<?php

namespace MyWishList\models;


use Illuminate\Database\Eloquent\Model;

class ReservationModel extends Model
{
    protected $table = "reservation";
    protected $primaryKey = "no";
    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo('\MyWishList\models\ItemModel', 'reservation_id');
    }
}