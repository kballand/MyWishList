<?php
namespace MyWishList\controllers;

use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\utils\SlimSingleton;
use MyWishList\views\BasicView;
use MyWishList\views\IndexView;
use MyWishList\views\ItemsDisplayView;
use MyWishList\views\ListCreationView;
use MyWishList\views\ListModificationView;
use MyWishList\views\ListDisplayView;
use MyWishList\views\NavBarView;
use MyWishList\views\NotFoundView;
use MyWishList\views\RedirectionView;
use MyWishList\views\RegisterView;
use Slim\Http\Request;

class DisplayController {
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new DisplayController();
        }
        return self::$instance;
    }

    public function displayLists() {
        $lists = ListModel::get();
        $view = new ListDisplayView($lists);
        $view = new BasicView(new NavBarView($view));
        return $view->render();
    }

    public function displayList($no) {
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        $list = ListModel::where('no', '=', $no)->first();
        $view = new ListDisplayView($list);
        $view = new BasicView(new NavBarView($view));
        return $view->render();
    }

    public function displayItem($id) {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $item = ItemModel::where('id', '=', $id)->first();
        $view = new ItemsDisplayView($item);
        $view = new BasicView(new NavBarView($view));
        return $view->render();
    }

    public function displayBookings($no) {

    }

    public function displayListBooking($no) {

    }

    public function displayIndex() {
        $view = new BasicView(new NavBarView(new IndexView()));
        return $view->render();
    }

    public function displayRegistration() {
        $view = new RegisterView();
        $view = new BasicView(new NavBarView($view));
        return $view->render();
    }

    public function displayNotFound($requestedUrl) {
        $requestedUrl = filter_var($requestedUrl, FILTER_SANITIZE_URL);
        $view = new NotFoundView($requestedUrl);
        return $view->render();
    }

    public function displayListCreation() {
        $view = new ListCreationView();
        $view = new BasicView(new NavBarView($view));
        return $view->render();
    }

    public function displayListModification(Request $request, $no) {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        $list = ListModel::where('no', '=', $no)->first();
        if(isset($list)) {
            $token = $request->getParam('token');
            if(isset($token)) {
                $modify_token = $list->modify_token;
                if($token === $modify_token) {
                    $view = new ListModificationView($list);
                } else {
                    $view = new RedirectionView($router->pathFor('index'), 'Echec de l\'accès à la modification de la liste !', 'Mauvais token de modification de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($router->pathFor('index'), 'Echec de l\'accès à la modification de la liste !', 'Absence du token de modification de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
            }
        } else {
            $view = new RedirectionView($router->pathFor('index'), 'Echec de l\'accès à la modification de la liste !', 'Cette liste n\'existe pas, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
        }
        $view = new BasicView(new NavBarView($view));
        return $view->render();
    }
}