<?php

namespace MyWishList\utils;

use Slim\App;

class SlimSingleton
{
    private static $instance;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $config = ['settings' => ['displayErrorDetails' => true]];
            self::$instance = new App($config);
        }
        return self::$instance;
    }
}