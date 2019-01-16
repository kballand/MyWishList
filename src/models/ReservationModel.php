<?php

namespace MyWishList\models;


use Illuminate\Database\Eloquent\Model;

/**
 * Classe représentant une réservation
 *
 * @package MyWishList\models
 */
class ReservationModel extends Model
{
    protected $table = "reservation";
    protected $primaryKey = "no";
    public $timestamps = false;

    public function item()
    {
        return $this->hasOne('\MyWishList\models\ItemModel', 'reservation_id');
    }

    public function account()
    {
        return $this->belongsTo('\MyWishList\models\AccountModel', 'purchaser');
    }
}