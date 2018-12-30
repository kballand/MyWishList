<?php
/**
 * Created by PhpStorm.
 * User: Killian
 * Date: 29/12/2018
 * Time: 22:38
 */

namespace MyWishList\views;


class IndexView implements IView {

    public function getRequiredCSS() {
        return ['/css/style.css'];
    }

    public function getRequiredScripts() {
        return [];
    }

    public function render() {
        return '';
    }
}