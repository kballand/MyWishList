<?php

namespace MyWishList\models;

use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    protected $table = "item";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function list()
    {
        return $this->belongsTo('\MyWishList\models\ListModel', 'list_id');
    }

    public function reservation()
    {
        return $this->belongsTo('\MyWishList\models\ReservationModel', 'reservation_id');
    }

    public function image()
    {
        return $this->belongsTo('\MyWishList\models\ImageModel', 'img');
    }
}