<?php
namespace MyWishList\controllers;


use MyWishList\models\ListModel;
use MyWishList\utils\SlimSingleton;
use MyWishList\views\BasicView;
use MyWishList\views\NavBarView;
use MyWishList\views\RedirectionView;
use Slim\Http\Request;

class ModifyController {
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new ModifyController();
        }
        return self::$instance;
    }

    public function modifyList(Request $request, $no) {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        $list = ListModel::where('no', '=', $no)->first();
        if(isset($list)) {
            $token = $request->getParam('token');
            if(isset($token)) {
                $modify_token = $list->modify_token;
                if($token === $modify_token) {
                    $queries = $request->getParsedBody();
                    if(isset($queries['title']) && isset($queries['description']) && isset($queries['expirationDate'])) {
                        $title = filter_var($queries['title'], FILTER_SANITIZE_STRING);
                        $description = filter_var($queries['description'], FILTER_SANITIZE_STRING);
                        $expirationDate = filter_var($queries['expirationDate'], FILTER_SANITIZE_STRING);
                        $list->title = $title;
                        $list->description = $description;
                        $list->expiration = $expirationDate;
                        $list->save();
                        $view = new RedirectionView($router->pathFor('list', ['no' => $list->no]) . "?token=$list->modify_token", 'Modification de la liste réussie avec succès !', 'Votre liste de souhaits à bien été modifiée, vous allez être redirigé vers celle-ci dans 5 secondes.');
                    } else {
                        $view = new RedirectionView($router->pathFor('list', ['no' => $list->no]) . "?token=$list->modify_token", 'Echec de la modification de la liste !', 'Une erreur est subvenue lors de la tentative de modification de la liste, vous allez être ridirigé vers celle-ci dans 5 secondes.');
                    }
                } else {
                    $view = new RedirectionView($router->pathFor('index'), 'Echec de la modification de la liste !', 'Mauvais token de modification de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($router->pathFor('index'), 'Echec de la modification de la liste !', 'Absence du token de modification de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
            }
        } else {
            $view = new RedirectionView($router->pathFor('index'), 'Echec de modification de la liste !', 'Cette liste n\'existe pas, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function modifyItem($id) {

    }

    public function createList(Request $request) {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $queries = $request->getParsedBody();
        if(isset($queries['title']) && isset($queries['description']) && isset($queries['expirationDate'])) {
            $title = filter_var($queries['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($queries['description'], FILTER_SANITIZE_STRING);
            $expirationDate = filter_var($queries['expirationDate'], FILTER_SANITIZE_STRING);
            $list = new ListModel();
            $list->title = $title;
            $list->description = $description;
            $list->expiration = $expirationDate;
            $list->modify_token = bin2hex(random_bytes(8));
            $list->save();
            $view = new RedirectionView($router->pathFor('list', ['no' => $list->no]) . "?token=$list->modify_token", 'Création de la liste réussie avec succès !', 'Votre liste de souhaits à bien été crée, vous allez être redirigé vers celle-ci dans 5 secondes.');
        } else {
            $view = new RedirectionView($router->pathFor('index'), 'Echec de la création de la liste !', 'Une erreur est subvenue lors de la tentative de création de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }
}