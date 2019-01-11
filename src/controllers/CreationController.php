<?php
namespace MyWishList\controllers;

use MyWishList\models\ListModel;
use MyWishList\utils\SlimSingleton;
use MyWishList\views\BasicView;
use MyWishList\views\NavBarView;
use MyWishList\views\RedirectionView;
use Slim\Http\Request;

class CreationController {
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new CreationController();
        }
        return self::$instance;
    }

    public function createList(Request $request) {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $queries = $request->getParsedBody();
        $indexPath = $router->pathFor('index');
        if(isset($queries['title']) && isset($queries['description']) && isset($queries['expirationDate'])) {
            $title = filter_var($queries['title'], FILTER_SANITIZE_STRING);
            if(strlen(trim($title)) > 0) {
                $expirationDate = filter_var($queries['expirationDate'], FILTER_SANITIZE_STRING);
                $timeDate = strtotime($expirationDate . ' +1 day');
                $timeNow = strtotime('now');
                if($timeDate && $timeDate > $timeNow) {
                    $description = filter_var($queries['description'], FILTER_SANITIZE_STRING);
                    $list = new ListModel();
                    $list->title = $title;
                    $list->description = $description;
                    $list->expiration = $expirationDate;
                    $list->modify_token = bin2hex(random_bytes(8));
                    do {
                        $list->access_token = bin2hex(random_bytes(8));
                    } while($list->access_token === $list->modify_token);
                    $list->save();
                    setcookie('mywishlist-' . $list->no, password_hash($list->modify_token, CRYPT_BLOWFISH, ['cost' => 12]), $timeDate);
                    $listPath = $router->pathFor('list', ['no' => $list->no]) . "?token=$list->modify_token";
                    $view = new RedirectionView($listPath, 'Création de la liste réussie avec succès !', 'Votre liste de souhaits à bien été crée, vous allez être redirigé vers celle-ci dans 5 secondes.');
                } else {
                    $view = new RedirectionView($indexPath, 'Echec de la création de la liste !', 'Le date de la liste est invallide, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
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

    public function createItem(Request $request) {

    }

    public function createPot() {

    }
}