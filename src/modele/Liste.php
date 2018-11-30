<?php

namespace MyWishList\modele;

use \Illuminate\Database\Eloquent\Model;

class Liste extends Model {
    protected $table = "Liste";
    protected $primaryKey = "no";
    public $timestamps = false;
}