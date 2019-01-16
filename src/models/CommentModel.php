<?php

namespace MyWishList\models;


use Illuminate\Database\Eloquent\Model;

/**
 * Classe représentant un commentaire
 *
 * @package MyWishList\models
 */
class CommentModel extends Model
{
    protected $table = "comment";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function list()
    {
        return $this->belongsTo('\MyWishList\models\ListModel', 'list_id');
    }

    public function account()
    {
        return $this->belongsTo('\MyWishList\models\AccountModel', 'sender');
    }
}