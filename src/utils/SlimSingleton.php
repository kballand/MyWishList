<?php
namespace MyWishList\utils;

use Slim\App;

class SlimSingleton {
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new App();
        }
        return self::$instance;
    }
}