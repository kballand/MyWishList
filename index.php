<?php

include_once "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager;
use MyWishList\modele\Item;
use MyWishList\modele\Liste;

$db = new Manager();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$items = Item::get();
foreach($items as $item) {
    echo $item . '<br />';
}
echo '<br />';
$listes = Liste::get();
foreach($listes as $liste) {
    echo $liste . '<br />';
}
