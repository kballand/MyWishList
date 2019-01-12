<?php

include_once "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager;
use MyWishList\controllers\CreationController;
use MyWishList\controllers\DisplayController;
use MyWishList\controllers\ModifyController;
use \Slim\Http\Response;
use \Slim\Http\Request;
use MyWishList\utils\SlimSingleton;

session_start();

date_default_timezone_set('Europe/Paris');

$db = new Manager();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app = SlimSingleton::getInstance();

$app->get('/list/display/{no}', function (Request $request, Response $response, $args) {
    $controller = DisplayController::getInstance();
    $content = $controller->displayList($request, $args['no']);
    $response->write($content);
})->setName('displayList');

$app->get('/lists', function (Request $request, Response $response) {
    $controller = DisplayController::getInstance();
    $content = $controller->displayLists();
    $response->write($content);
})->setName('displayLists');

$app->get('/list/items/{no}/display/{id}', function (Request $request, Response $response, $args) {
    $controller = DisplayController::getInstance();
    $content = $controller->displayItem($request, $args['no'], $args['id']);
    $response->write($content);
})->setName('displayItem');

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

$app->get('/', function(Request $request, Response $response) {
    $controller = DisplayController::getInstance();
    $response->write($controller->displayIndex());
})->setName('index');

$app->get('/register', function(Request $request, Response $response) {
    $controller = DisplayController::getInstance();
    $response->write($controller->displayRegistration());
})->setName('registration');

$app->get('/list/create', function(Request $request, Response $response) {
    $controller = DisplayController::getInstance();
    $response->write($controller->displayListCreation());
})->setName('createList');

$app->post('/list/create', function(Request $request, Response $response) {
    $controller = CreationController::getInstance();
    $response->write($controller->createList($request));
});

$app->get('/list/modify/{no}', function(Request $request, Response $response, $args) {
   $controller = DisplayController::getInstance();
   $response->write($controller->displayListModification($request, $args['no']));
})->setName('modifyList');

$app->post('/list/modify/{no}', function(Request $request, Response $response, $args) {
   $controller = ModifyController::getInstance();
   $response->write($controller->modifyList($request, $args['no']));
});

$app->get('/list/delete/{no}', function(Request $request, Response $response, $args) {
    $controller = ModifyController::getInstance();
    $response->write($controller->deleteList($request, $args['no']));
})->setName('deleteList');

$app->get('/list/items/{no}/add', function(Request $request, Response $response, $args) {
    $controller = DisplayController::getInstance();
    $response->write($controller->displayItemCreation($request, $args['no']));
})->setName('addItem');

$app->post('/list/items/{no}/add', function(Request $request, Response $response, $args) {
   $controller = CreationController::getInstance();
   $response->write($controller->createItem($request, $args['no']));
});

$app->get('/list/items/{no}/modify/{id}', function(Request $request, Response $response, $args) {
    $controller = DisplayController::getInstance();
    $response->write($controller->displayItemModification($request, $args['no'], $args['id']));
})->setName('modifyItem');

$app->post('/list/items/{no}/modify/{id}', function(Request $request, Response $response, $args) {
    $controller = ModifyController::getInstance();
    $response->write($controller->modifyItem($request, $args['no'], $args['id']));
});

$app->get('/list/items/{no}/delete/{id}', function(Request $request, Response $response, $args) {
    $controller = ModifyController::getInstance();
    $response->write($controller->deleteItem($request, $args['no'], $args['id']));
})->setName('deleteItem');

$app->get('/list/items/{no}/reserve/{id}', function(Request $request, Response $response, $args) {
    $controller = DisplayController::getInstance();
    $response->write($controller->displayItemReservation($request, $args['no'], $args['id']));
})->setName('reserveItem');

$app->post('/list/items/{no}/reserve/{id}', function(Request $request, Response $response, $args) {
   $controller = CreationController::getInstance();
   $response->write($controller->reserveItem($request, $args['no'], $args['id']));
});

$app->run();