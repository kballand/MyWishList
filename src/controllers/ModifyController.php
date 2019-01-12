<?php

namespace MyWishList\controllers;


use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\utils\CommonUtils;
use MyWishList\utils\SlimSingleton;
use MyWishList\views\BasicView;
use MyWishList\views\NavBarView;
use MyWishList\views\RedirectionView;
use Slim\Http\Request;

class ModifyController
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ModifyController();
        }
        return self::$instance;
    }

    public function modifyList(Request $request, $no)
    {
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec de la modification de la liste !', true);
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $queries = $request->getParsedBody();
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
            if (isset($queries['title']) && isset($queries['description']) && isset($queries['expirationDate'])) {
                $title = filter_var($queries['title'], FILTER_SANITIZE_STRING);
                if (strlen(trim($title)) > 0) {
                    $expirationDate = filter_var($queries['expirationDate'], FILTER_SANITIZE_STRING);
                    $timeDate = strtotime($expirationDate);
                    $timeNow = strtotime('now');
                    if ($timeDate && $timeDate > $timeNow) {
                        $description = filter_var($queries['description'], FILTER_SANITIZE_STRING);
                        $list->title = $title;
                        $list->description = $description;
                        $list->expiration = $expirationDate;
                        $list->save();
                        setcookie('mywishlist-' . $list->no, password_hash($list->modify_token, CRYPT_BLOWFISH, ['cost' => 12]), $timeDate, '/');
                        $view = new RedirectionView($listPath, 'Modification de la liste réussie avec succès !', 'Votre liste de souhaits à bien été modifiée, vous allez être redirigé vers celle-ci dans 5 secondes.');
                    } else {
                        $view = new RedirectionView($listPath, 'Echec de la modification de la liste !', 'Le date de la liste est invallide, vous allez être ridirigé vers celle-ci dans 5 secondes.');
                    }
                } else {
                    $view = new RedirectionView($listPath, 'Echec de la modification de la liste !', 'Le titre de la liste ne peut pas être vide, vous allez être ridirigé vers celle-ci dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($listPath, 'Echec de la modification de la liste !', 'Une erreur est subvenue lors de la tentative de modification de la liste, vous allez être ridirigé vers celle-ci dans 5 secondes.');
            }
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function modifyItem(Request $request, $no, $id)
    {
        $canModify = CommonUtils::canAccessItem($request, $no, $id, 'Echec de la modification de l\'item !', true);
        if ($canModify instanceof ItemModel) {
            $item = $canModify;
            $queries = $request->getParsedBody();
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $listPath = $router->pathFor('displayList', ['no' => $item->list->no]) . "?token={$item->list->modify_token}";
            if (isset($queries['name']) && isset($queries['description']) && isset($queries['website']) && isset($queries['price'])) {
                $name = filter_var($queries['name'], FILTER_SANITIZE_STRING);
                if (strlen(trim($name)) > 0) {
                    $price = filter_var($queries['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if ($price && $price >= 0.01 && $price <= 999.99) {
                        $url = filter_var($queries['website'], FILTER_SANITIZE_URL);
                        if (empty($url) || filter_var($url, FILTER_VALIDATE_URL) !== false) {
                            $description = filter_var($queries['description'], FILTER_SANITIZE_STRING);
                            $item->name = $name;
                            $item->description = $description;
                            $item->price = $price;
                            $item->url = $url;
                            $item->save();
                            $itemPath = $router->pathFor('displayItem', ['no' => $item->list->no, 'id' => $item->id]) . "?token={$item->list->modify_token}";
                            $view = new RedirectionView($itemPath, 'Modification de l\'item réussie avec succès !', 'Votre item a bien été modifié, vous allez être redirigé vers celui-ci dans 5 secondes.');
                        } else {
                            $view = new RedirectionView($listPath, 'Echec de la modification de l\'item !', 'Le site web détaillant l\'item est invalide, vous allez être ridirigé vers votre liste dans 5 secondes.');
                        }
                    } else {
                        $view = new RedirectionView($listPath, 'Echec de la modification de l\'item !', 'Le prix de l\'item est invalide, vous allez être ridirigé vers votre liste dans 5 secondes.');
                    }
                } else {
                    $view = new RedirectionView($listPath, 'Echec de la modification de l\'item !', 'Le nom de l\'item ne peut pas être vide, vous allez être ridirigé vers votre liste dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($listPath, 'Echec de la modification de la liste !', 'Une erreur est subvenue lors de la tentative de modification de la liste, vous allez être ridirigé vers celle-ci dans 5 secondes.');
            }
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function deleteList(Request $request, $no)
    {
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec de la suppression de la liste !', true);
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            unset($_COOKIE['mywishlist' . $list->no]);
            setcookie('mywishlist-' . $list->no, null, -1, '/');
            $list->delete();
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $indexPath = $router->pathFor('index');
            $view = new RedirectionView($indexPath, 'Suppression de la liste réussie avec succès !', 'Votre liste de souhaits a bien été supprimée, vous allez être redirigé vers l\'accueil dans 5 secondes.');
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function deleteItem(Request $request, $no, $id)
    {
        $canModify = CommonUtils::canAccessItem($request, $no, $id, 'Echec de la suppression de l\'item !', true);
        if ($canModify instanceof ItemModel) {
            $item = $canModify;
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $listPath = $router->pathFor('displayList', ['no' => $item->list->no]) . "?token={$item->list->modify_token}";
            $item->delete();
            $view = new RedirectionView($listPath, 'Suppression de l\'item réussi avec succès !', 'Votre item a bien été supprimé, vous allez être redirigé vers votre liste dans 5 secondes.');
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }
}