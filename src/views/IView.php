<?php

namespace MyWishList\views;

interface IView
{

    public function getRequiredCSS();

    public function getRequiredScripts();

    public function render();

}