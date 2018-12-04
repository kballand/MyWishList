<?php
namespace MyWishList\controllers;


class ShareController {
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            $instance = new ShareController();
        }
        return $instance;
    }

    public function shareList($no) {

    }
}