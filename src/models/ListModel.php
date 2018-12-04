<?php
namespace MyWishList\models;

use \Illuminate\Database\Eloquent\Model;

class ListModel extends Model {
    protected $table = "list";
    protected $primaryKey = "no";
    public $timestamps = false;

    public function __toString() {
        return json_encode($this, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function items() {
        return $this->hasMany('\MyWishList\modele\Item', 'liste_id');
    }
}