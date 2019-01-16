<?php

namespace MyWishList\models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe représentant une liste
 *
 * @package MyWishList\models
 */
class ListModel extends Model
{
    protected $table = "list";
    protected $primaryKey = "no";
    public $timestamps = false;

    public function items()
    {
        return $this->hasMany('\MyWishList\models\ItemModel', 'list_id');
    }

    public function comments()
    {
        return $this->hasMany('\MyWishList\models\CommentModel', 'list_id');
    }

    public function owner()
    {
        return $this->belongsTo('\MyWishList\models\AccountModel', 'owner_name');
    }
}