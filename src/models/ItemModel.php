<?php
namespace MyWishList\models;

use \Illuminate\Database\Eloquent\Model;

class ItemModel extends Model {
    protected $table = "item";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function __toString() {
        return json_encode($this, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function liste() {
        return $this->belongsTo('\MyWishList\models\ListModel', 'liste_id');
    }
}