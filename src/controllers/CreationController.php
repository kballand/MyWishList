<?php
namespace MyWishList\controllers;

class CreationController {
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new CreationController();
        }
        return self::$instance;
    }

    public function createList() {

    }

    public function createItem() {

    }

    public function createPot() {

    }
}