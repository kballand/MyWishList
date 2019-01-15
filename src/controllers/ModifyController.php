<?php

namespace MyWishList\controllers;


use MyWishList\models\AccountModel;
use MyWishList\models\ImageModel;
use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\utils\Authentication;
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
            $itemPath = $router->pathFor('displayItem', ['no' => $item->list->no, 'id' => $item->id]) . "?token={$item->list->modify_token}";
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
                            if (isset($_FILES['imageUpload']) && is_uploaded_file($_FILES['imageUpload']['tmp_name']) && file_exists($_FILES['imageUpload']['tmp_name'])) {
                                $file = $_FILES['imageUpload'];
                                $tmpName = $file['tmp_name'];
                                if (getimagesize($tmpName) !== false) {
                                    $name = basename($file['name']);
                                    if ($name > 255 && file_exists('img/' . $name)) {
                                        $name = substr($name, 0, 251);
                                    } else if ($name > 255) {
                                        $name = substr($name, 0, 255);
                                    }
                                    $finalName = $name;
                                    $count = 1;
                                    while (file_exists('img/' . $finalName) && !(md5_file('img/' . $finalName) === md5_file($tmpName))) {
                                        $finalName = $name . ' (' . $count . ')';
                                        ++$count;
                                    }
                                    if (file_exists('img/' . $finalName)) {
                                        $image = ImageModel::where('basename', '=', $finalName)->first();
                                        if (!isset($image)) {
                                            $image = new ImageModel();
                                            $image->basename = $finalName;
                                            $image->uploaded = true;
                                            $image->local = false;
                                            $image->save();
                                        }
                                        $lastImage = $item->image;
                                        $item->img = $finalName;
                                        $item->save();
                                        CommonUtils::deleteUnusedImage($lastImage);
                                        $view = new RedirectionView($itemPath, 'Modification de l\'item réussie avec succès !', 'Votre item a bien été modifié, vous allez être redirigé vers celui-ci dans 5 secondes.');
                                    } else {
                                        if (move_uploaded_file($tmpName, 'img/' . $finalName)) {
                                            $image = new ImageModel();
                                            $image->basename = $finalName;
                                            $image->uploaded = true;
                                            $image->local = false;
                                            $image->save();
                                            $lastImage = $item->image;
                                            $item->img = $finalName;
                                            $item->save();
                                            CommonUtils::deleteUnusedImage($lastImage);
                                            $view = new RedirectionView($itemPath, 'Modification de l\'item réussie avec succès !', 'Votre item a bien été modifié à votre liste, vous allez être redirigé vers celui-ci dans 5 secondes.');
                                        } else {
                                            $view = new RedirectionView($itemPath, 'Echec de la modification de l\'item !', 'Une erreur est survenue lors de l\'upload de l\'image, vous allez être ridirigé vers votre item dans 5 secondes.');
                                        }
                                    }
                                } else {
                                    $view = new RedirectionView($itemPath, 'Echec de la modification de l\'item !', 'L\'image fournie n\'existe pas, vous allez être ridirigé vers celui-ci dans 5 secondes.');
                                }
                            } else if (isset($_POST['imageHotlink']) && !empty($_POST['imageHotlink'])) {
                                $imageHotlink = filter_var($_POST['imageHotlink'], FILTER_SANITIZE_URL);
                                if (file_exists('img/' . $imageHotlink) || getimagesize($imageHotlink) !== false) {
                                    if (strlen($imageHotlink) <= 255) {
                                        if (file_exists('img/' . $imageHotlink)) {
                                            $image = ImageModel::where('basename', '=', $imageHotlink)->first();
                                            if (!isset($image)) {
                                                $image = new ImageModel();
                                                $image->basename = $imageHotlink;
                                                $image->uploaded = true;
                                                $image->local = false;
                                                $image->save();
                                            }
                                        } else {
                                            $image = new ImageModel();
                                            $image->basename = $imageHotlink;
                                            $image->uploaded = false;
                                            $image->local = false;
                                            $image->save();
                                        }
                                        $lastImage = $item->image;
                                        $item->img = $imageHotlink;
                                        $item->save();
                                        CommonUtils::deleteUnusedImage($lastImage);
                                        $view = new RedirectionView($itemPath, 'Modification de l\'item réussie avec succès !', 'Votre item a bien été modifié, vous allez être redirigé vers celui-ci dans 5 secondes.');
                                    } else {
                                        $view = new RedirectionView($itemPath, 'Echec de la modification de l\'item !', 'L\'url de l\'image fournie est trop long, vous allez être ridirigé vers votre item dans 5 secondes.');
                                    }
                                } else {
                                    $view = new RedirectionView($itemPath, 'Echec de la modification de l\'item !', 'L\'image fournie n\'existe pas, vous allez être ridirigé vers votre item dans 5 secondes.');
                                }
                            } else {
                                $image = $item->image;
                                $item->img = null;
                                $item->save();
                                CommonUtils::deleteUnusedImage($image);
                                $view = new RedirectionView($itemPath, 'Modification de l\'item réussie avec succès !', 'Votre item a bien été modifié, vous allez être redirigé vers celui-ci dans 5 secondes.');
                            }
                        } else {
                            $view = new RedirectionView($itemPath, 'Echec de la modification de l\'item !', 'Le site web détaillant l\'item est invalide, vous allez être ridirigé vers celui-ci dans 5 secondes.');
                        }
                    } else {
                        $view = new RedirectionView($itemPath, 'Echec de la modification de l\'item !', 'Le prix de l\'item est invalide, vous allez être ridirigé vers celui-ci dans 5 secondes.');
                    }
                } else {
                    $view = new RedirectionView($itemPath, 'Echec de la modification de l\'item !', 'Le nom de l\'item ne peut pas être vide, vous allez être ridirigé vers celui-ci dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($itemPath, 'Echec de la modification de l\'item !', 'Une erreur est subvenue lors de la tentative de modification de l\'item, vous allez être ridirigé vers celui-ci dans 5 secondes.');
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
            CommonUtils::deleteList($list);
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
            CommonUtils::deleteItem($item);
            $view = new RedirectionView($listPath, 'Suppression de l\'item réussi avec succès !', 'Votre item a bien été supprimé, vous allez être redirigé vers votre liste dans 5 secondes.');
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function deleteAccount() {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $indexPath = $router->pathFor('index');
        if(Authentication::hasProfile()) {
            $account = AccountModel::where('username', '=', Authentication::getProfile()['username'])->first();
            $comments = $account->comments;
            foreach ($comments as $comment) {
                $comment->delete();
            }
            $reservations = $account->reservations;
            foreach($reservations as $reservation) {
                if(!CommonUtils::hasExpired($reservation->item->list)) {
                    $reservation->delete();
                }
            }
            $lists = $account->lists;
            foreach ($lists as $list) {
                if(!CommonUtils::hasExpired($list)) {
                    CommonUtils::deleteList($list);
                }
            }
            $account->delete();
            Authentication::deleteProfile();
            $view = new RedirectionView($indexPath, 'Suppression du compte réussie avec succès !', 'Votre compte a bien été supprimé, vous allez être redirigé vers l\'accueil dans 5 secondes.');
        } else {
            $view = new RedirectionView($indexPath, 'Echec de la suppression du compte !', 'Vous devez être connecté pour pouvoir supprimer votre compte, vous allez être redirigé vers l\'accueil dans 5 secondes.');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }
}