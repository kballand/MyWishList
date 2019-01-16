<?php

namespace MyWishList\utils;

use Slim\App;

class SlimSingleton
{
    private static $instance;
    private $slim;
    private $router;
    private $basePath;

    private function __construct()
    {
        $config = ['settings' => ['displayErrorDetails' => true]];
        $this->slim = new App($config);
        $this->router = $this->slim->getContainer()->get('router');
        $this->basePath = '/';
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new SlimSingleton();
        }
        return self::$instance;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getSlim()
    {
        return $this->slim;
    }

    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    public function getBasePath()
    {
        return $this->basePath;
    }
}