<?php

namespace MyWishList\views;


class IndexView implements IView
{

    public function getRequiredCSS()
    {
        return ['/css/style.css'];
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
?>
<img
    src="../../img/Accueil.jpg"
    height="100%"
    width="100%"
/>
