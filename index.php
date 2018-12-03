<?php

include_once "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager;
use MyWishList\modele\Liste;
use MyWishList\modele\Item;
use \Slim\Http\Response;
use Slim\App;

$db = new Manager();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app = new App();

$app->get('/liste/{no}', function ($request, $response, $args) {
    $liste = Liste::where('no', '=', $args['no'])->first();
    if(isset($liste)) {
        $response->write($liste . '<br />');
    }
})->setName('liste');

$app->get('/listes', function ($request, $response) {
    global $app;
    $listes = Liste::get();
    $content = "";
    foreach($listes as $liste) {
        $href = $app->getContainer()->get('router')->pathFor('liste', ['no' => $liste->no]);
        $content .= "<a href='$href'>Liste $liste->no</a><br />";
    }
    $response->write($content);
});

$app->get('/item/{id}', function ($request, $response, $args) {
    $item = Item::where('id', '=', $args['id'])->first();
    if(isset($item)) {
        $response->write($item . '<br />');
    }
});

$app->getContainer()['notFoundHandler'] = function () {
    return function() {
        $response = new Response(404);
        return $response->write('test');
    };
};

$app->run();