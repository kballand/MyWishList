<?php

namespace MyWishList\utils;

use Slim\App;

class SlimSingleton
{
    /**
     * @var SlimSingleton Instance unique de la classe
     */
    private static $instance;
    /**
     * @var App Application slim
     */
    private $slim;
    /**
     * @var mixed Routeur de slim
     */
    private $router;
    /**
     * @var string Chemin d'accès de base au site
     */
    private $basePath;
    /**
     * @var string Chemin d'accès au repertoire parent
     */
    private $baseDir;

    private function __construct()
    {
        $config = ['settings' => ['displayErrorDetails' => true]];
        $this->slim = new App($config);
        $this->router = $this->slim->getContainer()->get('router');
        $this->basePath = '/';
        $this->baseDir = '/';
    }

    /**
     * Méthode d'accès à l'instance de la classe
     *
     * @return SlimSingleton Instance de la classe
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new SlimSingleton();
        }
        return self::$instance;
    }

    /**
     * Getter du router de Slim
     *
     * @return mixed Le router de Slim
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Getter de l'application de Slim
     *
     * @return App L'application de Slim
     */
    public function getSlim()
    {
        return $this->slim;
    }

    /**
     * Setter du chemin de base du site
     *
     * @param $basePath string Le nouveau chemin de base du site
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Getter du chemin de base du site
     *
     * @return string Le chemin de base du site
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Setter du chemin de base du site
     *
     * @param $baseDir string Repertoire parent
     */
    public function setBaseDir($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * Getter du chemin de base du site
     *
     * @return string Le chemin du repertoire du site
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }
}