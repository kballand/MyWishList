<?php

include_once "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager;
use MyWishList\modele\Liste;
use MyWishList\modele\Item;
use Slim\App;

$db = new Manager();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app = new App();
$app->get('/listes', function ($request, $response, $args) {
    $listes = Liste::get();
    $content = "";
    foreach($listes as $liste) {
        $content .= $liste . '<br />';
    }
    $response->write($content);
});

$app->get('/liste/{no}', function ($request, $response, $args) {
    $liste = Liste::where('no', '=', $args['no'])->first();
    if(isset($liste)) {
        $response->write($liste . '<br />');
    }
});

$app->get('/item/{id}', function ($request, $response, $args) {
    $item = Item::where('id', '=', $args['id'])->first();
    if(isset($item)) {
        $response->write($item . '<br />');
    }
});

$app->run();