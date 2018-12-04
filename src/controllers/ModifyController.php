<?php
namespace MyWishList\controllers;


class ModifyController {
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new ModifyController();
        }
        return self::$instance;
    }

    public function modifyList($no) {

    }

    public function modifyItem($id) {

    }
}