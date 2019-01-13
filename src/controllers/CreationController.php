<?php

namespace MyWishList\controllers;

use MyWishList\models\CommentModel;
use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\models\ReservationModel;
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
                $timeDate = strtotime($expirationDate);
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
                    setcookie('mywishlist-' . $list->no, password_hash($list->modify_token, CRYPT_BLOWFISH, ['cost' => 12]), $timeDate, '/');
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
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec de la création de l\'item !', true);
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
                        $url = filter_var($queries['website'], FILTER_SANITIZE_URL);
                        if (empty($url) || filter_var($url, FILTER_VALIDATE_URL) !== false) {
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

    public function reserveItem(Request $request, $no, $id)
    {
        $canReserve = CommonUtils::canReserveItem($request, $no, $id, 'Echec de la réservation de l\'item !');
        if ($canReserve instanceof ItemModel) {
            $item = $canReserve;
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $itemPath = $router->pathFor('displayItem', ['no' => $item->list->no, 'id' => $item->id]) . "?token={$item->list->access_token}";
            $queries = $request->getParsedBody();
            if (isset($queries['name']) && isset($queries['message'])) {
                $name = filter_var($queries['name'], FILTER_SANITIZE_STRING);
                if (strlen(trim($name)) > 0) {
                    $message = filter_var($queries['message'], FILTER_SANITIZE_STRING);
                    $reservation = new ReservationModel();
                    $reservation->participant = $name;
                    $reservation->message = $message;
                    $reservation->save();
                    $item->reservation_id = $reservation->no;
                    $item->save();
                    $_SESSION['participantName'] = $name;
                    $view = new RedirectionView($itemPath, 'Réservation de l\'item réussie avec succès !', 'L\'item a bien été réservé, vous allez être redirigé vers celui-ci dans 5 secondes.');
                } else {
                    $view = new RedirectionView($itemPath, 'Echec de la réservation de l\'item !', 'Le nom de participation ne peut pas être vide, vous allez être ridirigé vers l\'item dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($itemPath, 'Echec de la réservation de l\'item !', 'Une erreur est subvenue lors de la tentative de réservation de la l\'item, vous allez être ridirigé vers celui-ci dans 5 secondes.');
            }
        } else {
            $view = $canReserve;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function commentList(Request $request, $no)
    {
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec de l\'ajout du commentaire à la liste !', false, $modificationGranted);
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $listPath = $router->pathFor('displayList', ['no' => $list->no]) . '?token=';
            if ($modificationGranted || CommonUtils::ownList($list)) {
                if ($modificationGranted) {
                    $listPath .= $list->modify_token;
                } else {
                    $listPath .= $list->access_token;
                }
                $view = new RedirectionView($listPath, 'Echec de l\'ajout du commentaire à la liste !', 'Vous ne pouvez pas commenter votre propre liste, vous allez être redirigé vers celui-ci dans 5 secondes.');
            } else {
                $listPath .= $list->access_token;
                if (!CommonUtils::hasExpired($list)) {
                    $queries = $request->getParsedBody();
                    if (isset($queries['comment'])) {
                        $commentMessage = filter_var($queries['comment'], FILTER_SANITIZE_STRING);
                        if (strlen(trim($commentMessage)) > 0) {
                            $comment = new CommentModel();
                            $comment->list_id = $list->no;
                            $comment->comment = $commentMessage;
                            $comment->save();
                            $view = new RedirectionView($listPath, 'Ajout du commentaire réussi avec succès !', 'Le commentaire a bien été ajouté à la liste, vous allez être redirigé vers celle-ci dans 5 secondes.');
                        } else {
                            $view = new RedirectionView($listPath, 'Echec de l\'ajout du commentaire à la liste !', 'Le commentaire ne peut pas être vide, vous allez être ridirigé vers la liste dans 5 secondes.');
                        }
                    } else {
                        $view = new RedirectionView($listPath, 'Echec de l\'ajout du commentaire à la liste !', 'Une erreur est subvenue lors de la tentative d\'ajout du commentaire, vous allez être ridirigé vers la liste dans 5 secondes.');
                    }
                } else {
                    $view = new RedirectionView($listPath, 'Echec de l\'ajout du commentaire à la liste !', 'Cette liste a expiré, vous allez être redirigé vers celui-ci dans 5 secondes.');
                }
            }
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }
}