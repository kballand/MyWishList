<?php

namespace MyWishList\controllers;

use MyWishList\exceptions\AuthException;
use MyWishList\models\AccountModel;
use MyWishList\models\CommentModel;
use MyWishList\models\ImageModel;
use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\models\ReservationModel;
use MyWishList\utils\Authentication;
use MyWishList\utils\CommonUtils;
use MyWishList\utils\SlimSingleton;
use MyWishList\views\BasicView;
use MyWishList\views\NavBarView;
use MyWishList\views\RedirectionView;
use Slim\Http\Request;

/**
 * Classe permettant de gérer les créations (items, listes...)
 *
 * @package MyWishList\controllers
 */
class CreationController
{
    /**
     * @var CreationController Instance unique de la classe
     */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * Methode d'accès à l'instance de la classe
     * @return CreationController L'instance de la classe
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CreationController();
        }
        return self::$instance;
    }

    /**
     * Methode permettant de creer une liste
     *
     * @param Request $request Requête envoyée par le client
     * @return string La rendu de la vue
     * @throws \Exception
     */
    public function createList(Request $request)
    {
        $router = SlimSingleton::getInstance()->getRouter();
        $indexPath = $router->pathFor('index');
        if (Authentication::hasProfile() && Authentication::getProfile()['participant']) {
            $view = new RedirectionView($indexPath, 'Echec de la création de la liste !', 'Vous ne pouvez pas créer de liste avec un compte participant, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
        } else {
            $queries = $request->getParsedBody();
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
                        if (Authentication::hasProfile()) {
                            $list->owner_name = Authentication::getProfile()['username'];
                        }
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
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }


    /**
     * Methode permettant de creer un item
     *
     * @param Request $request Requête envoyée par le client
     * @param $no int N° de la liste sur laquelle ajouter l'item
     * @return string Le rendu de la vue
     */
    public function createItem(Request $request, $no)
    {
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec de la création de l\'item !', true);
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $router = SlimSingleton::getInstance()->getRouter();
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
                                        $item->img = $finalName;
                                        $item->save();
                                        $view = new RedirectionView($listPath, 'Création de l\'item réussie avec succès !', 'Votre item a bien été ajouté à votre liste, vous allez être redirigé vers votre liste dans 5 secondes.');
                                    } else {
                                        if (move_uploaded_file($tmpName, 'img/' . $finalName)) {
                                            $image = new ImageModel();
                                            $image->basename = $finalName;
                                            $image->uploaded = true;
                                            $image->local = false;
                                            $image->save();
                                            $item->img = $finalName;
                                            $item->save();
                                            $view = new RedirectionView($listPath, 'Création de l\'item réussie avec succès !', 'Votre item a bien été ajouté à votre liste, vous allez être redirigé vers votre liste dans 5 secondes.');
                                        } else {
                                            $view = new RedirectionView($listPath, 'Echec de la création de l\'item !', 'Une erreur est survenue lors de l\'upload de l\'image, vous allez être ridirigé vers votre liste dans 5 secondes.');
                                        }
                                    }
                                } else {
                                    $view = new RedirectionView($listPath, 'Echec de la création de l\'item !', 'L\'image fournie n\'existe pas, vous allez être ridirigé vers votre liste dans 5 secondes.');
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
                                        $item->img = $imageHotlink;
                                        $item->save();
                                        $view = new RedirectionView($listPath, 'Création de l\'item réussie avec succès !', 'Votre item a bien été ajouté à votre liste, vous allez être redirigé vers votre liste dans 5 secondes.');
                                    } else {
                                        $view = new RedirectionView($listPath, 'Echec de la création de l\'item !', 'L\'url de l\'image fournie est trop long, vous allez être ridirigé vers votre liste dans 5 secondes.');
                                    }
                                } else {
                                    $view = new RedirectionView($listPath, 'Echec de la création de l\'item !', 'L\'image fournie n\'existe pas, vous allez être ridirigé vers votre liste dans 5 secondes.');
                                }
                            } else {
                                $item->save();
                                $view = new RedirectionView($listPath, 'Création de l\'item réussie avec succès !', 'Votre item a bien été ajouté à votre liste, vous allez être redirigé vers votre liste dans 5 secondes.');
                            }
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

    /**
     * Methode permettant de reserver un item
     *
     * @param Request $request Requête envoyé par le client
     * @param $no int N° de la liste dans laquelle se trouve l'item
     * @param $id int ID de l'item a réserver
     * @return string Le rendu de la vue
     */
    public function reserveItem(Request $request, $no, $id)
    {
        $canReserve = CommonUtils::canReserveItem($request, $no, $id, 'Echec de la réservation de l\'item !');
        if ($canReserve instanceof ItemModel) {
            $item = $canReserve;
            $router = SlimSingleton::getInstance()->getRouter();
            $itemPath = $router->pathFor('displayItem', ['no' => $item->list->no, 'id' => $item->id]) . "?token={$item->list->access_token}";
            $queries = $request->getParsedBody();
            if ((isset($queries['name']) || Authentication::hasProfile()) && isset($queries['message'])) {
                if (Authentication::hasProfile() || strlen(trim(filter_var($queries['name'], FILTER_SANITIZE_STRING))) > 0) {
                    $message = filter_var($queries['message'], FILTER_SANITIZE_STRING);
                    $reservation = new ReservationModel();
                    if (Authentication::hasProfile()) {
                        $reservation->participant = Authentication::getProfile()['username'];
                        $reservation->purchaser = $reservation->participant;
                    } else {
                        $reservation->participant = filter_var($queries['name'], FILTER_SANITIZE_STRING);
                    }
                    $reservation->message = $message;
                    $reservation->save();
                    $item->reservation_id = $reservation->no;
                    $item->save();
                    $_SESSION['participantName'] = $reservation->participant;
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

    /**
     * Methode permettant d ajouter un commentaire a une liste
     *
     * @param Request $request Requête envoyée par le client
     * @param $no int N° de la liste sur laquelle ajouter le commentaire
     * @return string Le rendu de la vue
     */
    public function commentList(Request $request, $no)
    {
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec de l\'ajout du commentaire à la liste !', false, $modificationGranted);
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $router = SlimSingleton::getInstance()->getRouter();
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
                            if (Authentication::hasProfile()) {
                                $comment->sender = Authentication::getProfile()['username'];
                            }
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

    /**
     * Methode permettant de creer un compte
     *
     * @param Request $request Requête envoyée par le client
     * @return string Le rendu de la vue
     */
    public function createAccount(Request $request)
    {
        $router = SlimSingleton::getInstance()->getRouter();
        $indexPath = $router->pathFor('index');
        if (!Authentication::hasProfile()) {
            $queries = $request->getParsedBody();
            if (isset($queries['firstName']) && isset($queries['lastName']) && isset($queries['username']) && isset($queries['email']) && isset($queries['password'])) {
                $firstName = $queries['firstName'];
                $lastName = $queries['lastName'];
                if (filter_var($queries['firstName'], FILTER_SANITIZE_STRING) === $firstName && filter_var($queries['lastName'], FILTER_SANITIZE_STRING) === $lastName) {
                    $firstName = filter_var($firstName, FILTER_SANITIZE_URL);
                    $lastName = filter_var($lastName, FILTER_SANITIZE_URL);
                    if (!empty(trim($firstName)) && !empty(trim($lastName))) {
                        $username = $queries['username'];
                        if (filter_var($queries['username'], FILTER_SANITIZE_STRING) === $username) {
                            $username = filter_var($queries['username'], FILTER_SANITIZE_STRING);
                            if (!empty(trim($username)) && trim($username) === $username && strlen($username) <= 20 && strlen($username) >= 5) {
                                $possibleAccount = AccountModel::where('username', '=', $username)->first();
                                if (!isset($possibleAccount)) {
                                    $email = $queries['email'];
                                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                                        if (!empty(trim($email))) {
                                            $password = $queries['password'];
                                            if (filter_var($password, FILTER_SANITIZE_STRING) == $password) {
                                                $password = filter_var($password, FILTER_SANITIZE_STRING);
                                                if (!empty(trim($password)) && trim($password) === $password && strlen($password) >= 7 && strtolower($password) !== $password && strtoupper($password) !== $password && preg_match('/[0-9]/', $password)) {
                                                    if (isset($queries['participant'])) {
                                                        $participant = true;
                                                    } else {
                                                        $participant = false;
                                                    }
                                                    $account = new AccountModel();
                                                    $account->first_name = $firstName;
                                                    $account->last_name = $lastName;
                                                    $account->username = $username;
                                                    $account->email = $email;
                                                    $account->password = password_hash($password, CRYPT_BLOWFISH, ['cost' => 12]);
                                                    $account->participant = $participant;
                                                    $account->save();
                                                    Authentication::loadProfile($account);
                                                    $view = new RedirectionView($indexPath, 'Création du compte réussie avec succès !', 'Votre compte a bien été créé, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                                                } else {
                                                    $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'Le mot de passe saisi n\'est pas de la bonne forme, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                                                }
                                            } else {
                                                $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'Le mot de passe saisi est incorrect, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                                            }
                                        } else {
                                            $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'L\'adresse email saisie ne peut être vide, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                                        }
                                    } else {
                                        $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'L\'adresse email saisie est incorrect, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                                    }
                                } else {
                                    $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'Ce nom d\'utilisateur est déjà pris, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                                }
                            } else {
                                $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'Le nom d\'utilisateur saisi n\'est pas de la bonne forme, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                            }
                        } else {
                            $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'Le nom d\'utilisateur saisi est incorrect, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                        }
                    } else {
                        $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'Le prénom et nom de famille saisi ne doivent pas être vide, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                    }
                } else {
                    $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'Le prénom ou nom de famille saisi est incorrect, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'Une erreur est survenue lors de la création du compte, vous allez être redirigé vers l\'accueil dans 5 secondes.');
            }
        } else {
            $view = new RedirectionView($indexPath, 'Echec de la création d\'un compte !', 'Vous possédez déjà un compte, vous allez être redirigé vers l\'accueil dans 5 secondes.');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    /**
     * Methode permettant de se connecter
     *
     * @param Request $request Requête envoyée par le client
     * @return string Le rendu de la vue
     */
    public function makeConnection(Request $request)
    {
        $router = SlimSingleton::getInstance()->getRouter();
        $indexPath = $router->pathFor('index');
        if (!Authentication::hasProfile()) {
            $queries = $request->getParsedBody();
            if (isset($queries['username']) && isset($queries['password'])) {
                if (filter_var($queries['username'], FILTER_SANITIZE_STRING) === $queries['username']) {
                    $username = filter_var($queries['username'], FILTER_SANITIZE_STRING);
                    if (filter_var($queries['password'], FILTER_SANITIZE_STRING) === $queries['password']) {
                        $password = filter_var($queries['password'], FILTER_SANITIZE_STRING);
                        try {
                            $account = Authentication::authenticate($username, $password);
                            Authentication::loadProfile($account);
                            $view = new RedirectionView($indexPath, 'Connection au compte réussie avec succès', 'Vous vous êtes bien connecté à votre compte, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                        } catch (AuthException $ex) {
                            $view = new RedirectionView($indexPath, 'Echec de la connection au compte !', 'Compte inexistant, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                        }
                    } else {
                        $view = new RedirectionView($indexPath, 'Echec de la connection au compte !', 'Mot de passe invalide, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                    }
                } else {
                    $view = new RedirectionView($indexPath, 'Echec de la connection au compte !', 'Nom d\'utilisateur invalide, vous allez être redirigé vers l\'accueil dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($indexPath, 'Echec de la connection au compte !', 'Une erreur est survenue lors de la tentative de connection au compte, vous allez être redirigé vers l\'accueil dans 5 secondes.');
            }

        } else {
            $view = new RedirectionView($indexPath, 'Echec de la connection au compte !', 'Vous êtes déjà connecté à un compte, vous allez être redirigé vers l\'accueil dans 5 secondes.');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }
}