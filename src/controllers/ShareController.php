<?php

namespace MyWishList\controllers;


class ShareController
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ShareController();
        }
        return self::$instance;
    }

    public function shareList($no)
    {

    }
}