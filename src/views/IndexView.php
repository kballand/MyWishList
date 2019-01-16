<?php

namespace MyWishList\views;


use MyWishList\utils\SlimSingleton;

/**
 * Vue représentant l'accueil du site
 *
 * @package MyWishList\views
 */
class IndexView implements IView
{

    public function getRequiredCSS()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return [$basePath . 'css/style.css'];
    }

    public function getRequiredScripts()
    {
        return [];
    }

    public function render()
    {
        return '';
    }
}
