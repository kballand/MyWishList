<?php

include_once "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager;
use MyWishList\controllers\DisplayController;
use MyWishList\models\ListModel;
use MyWishList\models\ItemModel;
use \Slim\Http\Response;
use MyWishList\utils\SlimSingleton;

$db = new Manager();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app = SlimSingleton::getInstance();

$app->get('/list/{no}', function ($request, $response, $args) {
    $controller = DisplayController::getInstance();
    $content = $controller->displayList($args['no']);
    $response->write($content);
})->setName('list');

$app->get('/lists', function ($request, $response) {
    $controller = DisplayController::getInstance();
    $content = $controller->displayLists();
    $response->write($content);
})->setName('lists');

$app->get('/item/{id}', function ($request, $response, $args) {
    $item = ItemModel::where('id', '=', $args['id'])->first();
    if(isset($item)) {
        $response->write($item . '<br />');
    }
})->setName('item');

$app->getContainer()['notFoundHandler'] = function () {
    return function() {
        $response = new Response(404);
        return $response->write('test');
    };
};

$app->get('/', function($request, $response) {
    $controller = DisplayController::getInstance();
    $response->write($controller->displayIndex());
})->setName('index');

$app->run();