<?php
namespace MyWishList\controllers;

use MyWishList\views\BasicView;

class DisplayController {
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            $instance = new DisplayController();
        }
        return $instance;
    }

    public function displayLists() {

    }

    public function displayList($no) {

    }

    public function displayItem($id) {

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