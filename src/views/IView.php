<?php

namespace MyWishList\views;

/**
 * Interface représentant une vue à afficher
 *
 * @package MyWishList\views
 */
interface IView
{
    /**
     * Méthode permettant de recupérer le CSS requis pour l'affichage
     * @return array CSS requis pour l'affichage
     */
    public function getRequiredCSS();

    /**
     * Méthode permettant de récupérer les scripts requis pour l'affichage
     * @return array Scripts requis pour l'affichage
     */
    public function getRequiredScripts();

    /**
     * Méthode de rendu de la vue
     *
     * @return string Le rendu de la vue
     */
    public function render();

}