<?php
namespace MyWishList\controllers;

use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\views\BasicView;
use MyWishList\views\ItemsDisplayView;
use MyWishList\views\ListsDisplayView;

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
        $view = new BasicView($view->render());
        return $view->render();
    }

    public function displayList($no) {
        $list = ListModel::where('no', '=', $no)->first();
        $view = new ListsDisplayView($list);
        $view = new BasicView($view->render());
        return $view->render();
    }

    public function displayItem($id) {
        $item = ItemModel::where('id', '=', $id)->first();
        $view = new ItemsDisplayView($item);
        $view = new BasicView($view->render());
        return $view->render();
    }

    public function displayBookings($no) {

    }

    public function displayListBooking($no) {

    }

    public function displayIndex() {
        $index = new BasicView('');
        return $index->render();
    }
}