<?php

namespace MyWishList\models;


use Illuminate\Database\Eloquent\Model;

class CommentModel extends Model
{
    protected $table = "comment";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function list()
    {
        return $this->belongsTo('\MyWishList\models\ListModel', 'list_id');
    }
}