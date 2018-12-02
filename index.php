<?php

include_once "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager;
use MyWishList\modele\Item;
use MyWishList\modele\Liste;
use Slim\Slim;

$db = new Manager();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app = new Slim();
$app->get('/listes', function () {
    $listes = Liste::get();
    foreach($listes as $liste) {
        echo $liste . '<br />';
    }
});

$app->get('/liste/:id', function ($id) {
    $liste = Liste::where('no', '=', $id)->first();
    if(isset($liste)) {
        echo $liste . '<br />';
    }
});

$app->run();

