<?php

namespace MyWishList\views;


use MyWishList\utils\SlimSingleton;

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