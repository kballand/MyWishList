<?php

namespace MyWishList\modele;

use \Illuminate\Database\Eloquent\Model;

class Item extends Model {
    protected $table = "Item";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function __toString() {
        return json_encode($this, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}