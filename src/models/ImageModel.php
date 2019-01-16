<?php

namespace MyWishList\models;


use Illuminate\Database\Eloquent\Model;

/**
 * Classe représentant une image d'un item
 *
 * @package MyWishList\models
 */
class ImageModel extends Model
{
    protected $table = "image";
    protected $primaryKey = "basename";
    public $timestamps = false;
    public $incrementing = false;

    public function items()
    {
        return $this->hasMany('\MyWishList\models\ItemModel', 'img');
    }
}