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

class CreationController
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CreationController();
        }
        return self::$instance;
    }

    public function createList(Request $request)
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $queries = $request->getParsedBody();
        $indexPath = $router->pathFor('index');
        if (isset($queries['title']) && isset($queries['description']) && isset($queries['expirationDate'])) {
            $title = filter_var($queries['title'], FILTER_SANITIZE_STRING);
            if (strlen(trim($title)) > 0) {
                $expirationDate = filter_var($queries['expirationDate'], FILTER_SANITIZE_STRING);
                $timeDate = strtotime($expirationDate . ' +1 day');
                $timeNow = strtotime('now');
                if ($timeDate && $timeDate > $timeNow) {
                    $description = filter_var($queries['description'], FILTER_SANITIZE_STRING);
                    $list = new ListModel();
                    $list->title = $title;
                    $list->description = $description;
                    $list->expiration = $expirationDate;
                    $list->modify_token = bin2hex(random_bytes(8));
                    do {
                        $list->access_token = bin2hex(random_bytes(8));
                    } while ($list->access_token === $list->modify_token);
                    $list->save();
                    setcookie('mywishlist-' . $list->no, password_hash($list->modify_token, CRYPT_BLOWFISH, ['cost' => 12]), $timeDate);
                    $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
                    $view = new RedirectionView($listPath, 'Création de la liste réussie avec succès !', 'Votre liste de souhaits à bien été crée, vous allez être redirigé vers celle-ci dans 5 secondes.');
                } else {
                    $view = new RedirectionView($indexPath, 'Echec de la création de la liste !', 'Le date de la liste est invalide, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($indexPath, 'Echec de la création de la liste !', 'Le titre de la liste ne peut pas être vide, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
            }
        } else {
            $view = new RedirectionView($indexPath, 'Echec de la création de la liste !', 'Une erreur est subvenue lors de la tentative de création de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function createItem(Request $request, $no)
    {
        $canModify = CommonUtils::canModifyList($request, $no, 'Echec de la création de l\'item !');
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
            $queries = $request->getParsedBody();
            if (isset($queries['name']) && isset($queries['description']) && isset($queries['price']) && isset($queries['website'])) {
                $name = filter_var($queries['name'], FILTER_SANITIZE_STRING);
                if (strlen(trim($name)) > 0) {
                    $price = filter_var($queries['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    if ($price && $price >= 0.01 && $price <= 999.99) {
                        if (filter_var($queries['website'], FILTER_VALIDATE_URL) !== false) {
                            $url = filter_var($queries['website'], FILTER_SANITIZE_URL);
                            $description = filter_var($queries['description'], FILTER_SANITIZE_STRING);
                            $item = new ItemModel();
                            $item->list_id = $list->no;
                            $item->name = $name;
                            $item->description = $description;
                            $item->price = $price;
                            $item->url = $url;
                            $item->save();
                            $view = new RedirectionView($listPath, 'Création de l\'item réussie avec succès !', 'Votre item a bien été ajouté à votre liste, vous allez être redirigé vers votre liste dans 5 secondes.');

                        } else {
                            $view = new RedirectionView($listPath, 'Echec de la création de l\'item !', 'Le site web détaillant l\'item est invalide, vous allez être ridirigé vers votre liste dans 5 secondes.');
                        }
                    } else {
                        $view = new RedirectionView($listPath, 'Echec de la création de l\'item !', 'Le prix de l\'item est invalide, vous allez être ridirigé vers votre liste dans 5 secondes.');
                    }
                } else {
                    $view = new RedirectionView($listPath, 'Echec de la création de l\'item !', 'Le nom de l\'item ne peut pas être vide, vous allez être ridirigé vers votre liste dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($listPath, 'Echec de la création de l\'item !', 'Une erreur est subvenue lors de la tentative de création de la l\'item, vous allez être ridirigé vers votre liste dans 5 secondes.');
            }
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function createPot()
    {

    }
}