<?php

include_once "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager;
use MyWishList\controllers\DisplayController;
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
    $controller = DisplayController::getInstance();
    $content = $controller->displayItem($args['id']);
    $response->write($content);
})->setName('item');

$app->getContainer()['notFoundHandler'] = function () {
    return function($request, $response) {
        $controller = DisplayController::getInstance();
        $uri = $request->getUri();
        $content = $controller->displayNotFound($uri->getPath());
        $response = new Response(404);
        $response->write($content);
        return $response;
    };
};

$app->get('/', function($request, $response) {
    $controller = DisplayController::getInstance();
    $response->write($controller->displayIndex());
})->setName('index');

$app->get('/register', function($request, $response) {
    $controller = DisplayController::getInstance();
    $response->write($controller->displayRegistration());
})->setName('registration');

$app->get('/create/list', function($request, $response) {
    $controller = DisplayController::getInstance();
    $response->write($controller->displayListCreation());
})->setName('creation');

$app->run();