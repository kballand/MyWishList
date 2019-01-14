<?php

namespace MyWishList\models;


use Illuminate\Database\Eloquent\Model;

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