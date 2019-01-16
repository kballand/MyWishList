<?php

namespace MyWishList\models;


use Illuminate\Database\Eloquent\Model;

/**
 * Classe représentant un compte
 *
 * @package MyWishList\models
 */
class AccountModel extends Model
{
    protected $table = "account";
    protected $primaryKey = "username";
    public $timestamps = false;
    public $incrementing = false;

    public function lists()
    {
        return $this->hasMany('\MyWishList\models\ListModel', 'owner_name');
    }

    public function reservations()
    {
        return $this->hasMany('\MyWishList\models\ReservationModel', 'purchaser');
    }

    public function comments()
    {
        return $this->hasMany('\MyWishList\models\CommentModel', 'sender');
    }
}