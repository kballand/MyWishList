<?php
namespace MyWishList\controllers;


class ModifyController {
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            $instance = new ModifyController();
        }
        return $instance;
    }

    public function modifyList($no) {

    }

    public function modifyItem($id) {

    }
}