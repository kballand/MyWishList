<?php

namespace MyWishList\modele;

use \Illuminate\Database\Eloquent\Model;

class Item extends Model {
    protected $table = "Item";
    protected $primaryKey = "id";
    public $timestamps = false;
}