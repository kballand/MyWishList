<?php

namespace MyWishList\controllers;


use MyWishList\models\ListModel;
use MyWishList\utils\CommonUtils;
use MyWishList\utils\SlimSingleton;
use MyWishList\views\BasicView;
use MyWishList\views\NavBarView;
use MyWishList\views\RedirectionView;
use Slim\Http\Request;

/**
 * Classe permettant de gérer le partage de liste
 *
 * @package MyWishList\controllers
 */
class ShareController
{
    /**
     * @var ShareController Instance unique de la classe
     */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * Methode d'accès à l'instance de la classe
     *
     * @return ShareController L'instance de la classe
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ShareController();
        }
        return self::$instance;
    }

    /**
     * Méthode permettant de partager une liste
     *
     * @param Request $request Requête envoyée par le client
     * @param $no int N° de la liste à partager
     * @return string Rendu de la vue
     * @throws \Exception
     */
    public function shareList(Request $request, $no)
    {
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec du partage de la liste !', true);
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $router = SlimSingleton::getInstance()->getRouter();
            $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
            if (!isset($list->access_token)) {
                do {
                    $list->access_token = bin2hex(random_bytes(8));
                } while ($list->access_token === $list->modify_token);
                $list->save();
                $view = new RedirectionView($listPath, 'Partage de la liste réussi avec succès !', 'La liste a bien été partagée, vous allez être redirigé vers celle-ci dans 5 secondes !');
            } else {
                $view = new RedirectionView($listPath, 'Echec du partage de la liste !', 'La liste est déjà partagée, vous allez être redirigé vers celle-ci dans 5 secondes !');
            }
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    /**
     * Méthode permettant de rendre une liste publique
     *
     * @param Request $request Requête envoyée par le client
     * @param $no int N° de la liste à publier
     * @return string Rendu de la vue
     */
    public function publicizeList(Request $request, $no)
    {
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec de la publication de la liste !', true);
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $router = SlimSingleton::getInstance()->getRouter();
            $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
            if (isset($list->access_token)) {
                if (!$list->public) {
                    $list->public = true;
                    $list->save();
                    $view = new RedirectionView($listPath, 'Publication de la liste réussie avec succès', 'La liste est bien devenue public, vous allez être redirigé vers celle-ci dans 5 secondes !');
                } else {
                    $view = new RedirectionView($listPath, 'Echec de la publication de la liste !', 'La liste est déjà publique, vous allez être redirigé vers celle-ci dans 5 secondes !');
                }
            } else {
                $view = new RedirectionView($listPath, 'Echec de la publication de la liste !', 'La liste doit d\'abord être partagée, vous allez être redirigé vers celle-ci dans 5 secondes !');
            }
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    /**
     * Méthode permettant de rendre privée une liste publique
     *
     * @param Request $request Requête envoyée par le client
     * @param $no int N° de la liste à rendre privée
     * @return string Rendu de la vue
     */
    public function privatizeList(Request $request, $no)
    {
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec de la privatisation de la liste !', true);
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $router = SlimSingleton::getInstance()->getRouter();
            $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
            if (isset($list->access_token)) {
                if ($list->public) {
                    $list->public = false;
                    $list->save();
                    $view = new RedirectionView($listPath, 'Privationsation de la liste réussie avec succès', 'La liste est bien redevenue privée, vous allez être redirigé vers celle-ci dans 5 secondes !');
                } else {
                    $view = new RedirectionView($listPath, 'Echec de la privatisation de la liste !', 'La liste est déjà privée, vous allez être redirigé vers celle-ci dans 5 secondes !');
                }
            } else {
                $view = new RedirectionView($listPath, 'Echec de la privatisation de la liste !', 'La liste doit d\'abord être partagée, vous allez être redirigé vers celle-ci dans 5 secondes !');
            }
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }
}