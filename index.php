<?php

include_once "vendor/autoload.php";

use Illuminate\Database\Capsule\Manager;
use MyWishList\controllers\DisplayController;
use MyWishList\controllers\ModifyController;
use MyWishList\controllers\ShareController;
use \Slim\Http\Response;
use \Slim\Http\Request;
use MyWishList\utils\SlimSingleton;

$db = new Manager();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app = SlimSingleton::getInstance();

$app->get('/list/display/{no}', function (Request $request, Response $response, $args) {
    $controller = DisplayController::getInstance();
    $content = $controller->displayList($args['no']);
    $response->write($content);
})->setName('list');

$app->get('/lists', function (Request $request, Response $response) {
    $controller = DisplayController::getInstance();
    $content = $controller->displayLists();
    $response->write($content);
})->setName('lists');

$app->get('/item/display/{id}', function (Request $request, Response $response, $args) {
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

$app->get('/liste/partage/{no}', function($token_partage){
    $controller = ShareController::getInstance();
    $ctrl->shareList($token_partage);
})->name('Msg2');

$app->post('/liste/partage/:token', function($token_partage){
    $ctrl=new ShareController::ge;
    $ctrl->setMsg();
});

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
    $controller = ModifyController::getInstance();
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

$app->get('/list/addItem/{no}', function(Request $request, Response $response, $args) {
    $controller = ModifyController::getInstance();
    $response->write($controller->addItem($response, $no));
})->setName('addItem');

$app->run();