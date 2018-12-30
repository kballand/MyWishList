<?php
namespace MyWishList\controllers;

use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\views\BasicView;
use MyWishList\views\IndexView;
use MyWishList\views\ItemsDisplayView;
use MyWishList\views\ListCreationView;
use MyWishList\views\ListsDisplayView;
use MyWishList\views\NavBarView;
use MyWishList\views\NotFoundView;
use MyWishList\views\RegisterView;

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
        $view = new ListsDisplayView($lists);
        $view = new BasicView(new NavBarView($view));
        return $view->render();
    }

    public function displayList($no) {
        $list = ListModel::where('no', '=', $no)->first();
        $view = new ListsDisplayView($list);
        $view = new BasicView(new NavBarView($view));
        return $view->render();
    }

    public function displayItem($id) {
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
        $view = new NotFoundView($requestedUrl);
        return $view->render();
    }

    public function displayListCreation() {
        $view = new ListCreationView();
        $view = new BasicView(new NavBarView($view));
        return $view->render();
    }
}