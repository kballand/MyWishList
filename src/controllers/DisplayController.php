<?php

namespace MyWishList\controllers;

use MyWishList\models\AccountModel;
use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\utils\Authentication;
use MyWishList\utils\CommonUtils;
use MyWishList\utils\SlimSingleton;
use MyWishList\views\AccountDisplayView;
use MyWishList\views\BasicView;
use MyWishList\views\IndexView;
use MyWishList\views\ItemCreationView;
use MyWishList\views\ItemDisplayView;
use MyWishList\views\ItemModificationView;
use MyWishList\views\ItemReservationView;
use MyWishList\views\ListCreationView;
use MyWishList\views\ListDisplayView;
use MyWishList\views\ListModificationView;
use MyWishList\views\LoginView;
use MyWishList\views\NavBarView;
use MyWishList\views\NotFoundView;
use MyWishList\views\RedirectionView;
use MyWishList\views\RegisterView;
use Slim\Http\Request;

class DisplayController
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DisplayController();
        }
        return self::$instance;
    }

    public function displayLists()
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $indexPath = $router->pathFor('index');
        if (Authentication::hasProfile() && Authentication::getProfile()['participant']) {
            $view = new RedirectionView($indexPath, 'Echec de l\'accès à vos listes !', 'Vous ne pouvez pas accèder à vos listes avec un compte participant, vous allez être redirigé vers l\'accueil dans 5 secondes.');
        } else if (Authentication::hasProfile()) {
            $account = AccountModel::where('username', '=', Authentication::getProfile()['username'])->first();
            $lists = $account->lists;
            $view = new ListDisplayView($lists, true);
        } else {
            $view = new RedirectionView($indexPath, 'Echec de l\'accès à vos listes !', 'Vous devez être connecté pour accéder à vos listes, vous allez être redirigé vers l\'accueil dans 5 secondes.');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayPublicLists()
    {
        $dateToday = new \DateTime('now');
        $dateToday = $dateToday->format('Y-m-d');
        $publicLists = ListModel::where('public', '=', true)->where('expiration', '>', $dateToday)->orderBy('expiration', 'ASC')->get();
        $view = new ListDisplayView($publicLists, false);
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayList(Request $request, $no)
    {
        $canAccess = CommonUtils::canAccessList($request, $no, 'Echec de l\'accès à la liste !', false, $modificationGranted);
        if ($canAccess instanceof ListModel) {
            $list = $canAccess;
            if ($modificationGranted) {
                $view = new ListDisplayView($list, true);
            } else {
                $view = new ListDisplayView($list, false);
            }
        } else {
            $view = $canAccess;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayItem(Request $request, $no, $id)
    {
        $canAccess = CommonUtils::canAccessItem($request, $no, $id, 'Echec de l\'accès à l\'item !', false, $modificationGranted);
        if ($canAccess instanceof ItemModel) {
            $item = $canAccess;
            if ($modificationGranted) {
                $view = new ItemDisplayView($item, true);
            } else {
                $view = new ItemDisplayView($item, false);
            }
        } else {
            $view = $canAccess;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayIndex()
    {
        $view = new IndexView();
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayRegistration()
    {
        if (!Authentication::hasProfile()) {
            $view = new RegisterView();
        } else {
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $indexPath = $router->pathFor('index');
            $view = new RedirectionView($indexPath, 'Echec de l\'accès à l\'enregistrement !', 'Vous êtes déjà connecter à votre compte, vous aller être redirigé vers l\'accueil dans 5 secondes !');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayNotFound($requestedUrl)
    {
        $requestedUrl = filter_var($requestedUrl, FILTER_SANITIZE_URL);
        $view = new NotFoundView($requestedUrl);
        return $view->render();
    }

    public function displayListCreation()
    {
        if (Authentication::hasProfile() && Authentication::getProfile()['participant']) {
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $indexPath = $router->pathFor('index');
            $view = new RedirectionView($indexPath, 'Echec de l\'accès à la création de liste !', 'Vous ne pouvez pas créer de liste avec un compte participant, vous allez être redirigé vers l\'accueil dans 5 secondes.');
        } else {
            $view = new ListCreationView();
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayListModification(Request $request, $no)
    {
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec de l\'accès à la modification de la liste !', true);
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $view = new ListModificationView($list);
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayItemCreation(Request $request, $no)
    {
        $canModify = CommonUtils::canAccessList($request, $no, 'Echec de l\'ajout d\'un item à la liste !', true);
        if ($canModify instanceof ListModel) {
            $view = new ItemCreationView();
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayItemModification(Request $request, $no, $id)
    {
        $canModify = CommonUtils::canAccessItem($request, $no, $id, 'Echec de l\'accès à la modification de l\'item !', true);
        if ($canModify instanceof ItemModel) {
            $item = $canModify;
            $view = new ItemModificationView($item);
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayItemReservation(Request $request, $no, $id)
    {
        $canReserve = CommonUtils::canReserveItem($request, $no, $id, 'Echec de l\'accès à la réservation de l\'item !');
        if ($canReserve instanceof ItemModel) {
            $item = $canReserve;
            $view = new ItemReservationView($item);
        } else {
            $view = $canReserve;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayReservations()
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $indexPath = $router->pathFor('index');
        if (Authentication::hasProfile()) {
            $account = AccountModel::where('username', '=', Authentication::getProfile()['username'])->first();
            $reservations = $account->reservations;
            $lists = [];
            foreach ($reservations as $reservation) {
                if (!in_array($reservation->item->list, $lists)) {
                    $lists[] = $reservation->item->list;
                }
            }
            $view = new ListDisplayView($lists, false);
        } else {
            $view = new RedirectionView($indexPath, 'Echec de l\'acces à la liste de vos participations !', 'Vous devez être connecté pour pouvoir afficher vos participations, vous allez être rédirigé vers l\'accueil dans 5 secondes.');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayLogout()
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $indexPath = $router->pathFor('index');
        if (Authentication::hasProfile()) {
            Authentication::deleteProfile();
            $view = new RedirectionView($indexPath, 'Déconnection réussie avec succès', 'Vous vous êtes bien déconnecté, vous allez être rédirigé vers l\'accueil dans 5 secondes.');
        } else {
            $view = new RedirectionView($indexPath, 'Echec de la déconnection !', 'Vous devez être connecté pour pouvoir vous déconnecter, vous allez être rédirigé vers l\'accueil dans 5 secondes.');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayLogin()
    {
        if (!Authentication::hasProfile()) {
            $view = new LoginView();
        } else {
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $indexPath = $router->pathFor('index');
            $view = new RedirectionView($indexPath, 'Echec de l\'accès à la page de connection !', 'Vous êtes déjà connecter à votre compte, vous aller être redirigé vers l\'accueil dans 5 secondes !');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayAccount()
    {
        if (Authentication::hasProfile()) {
            $view = new AccountDisplayView(AccountModel::where('username', '=', Authentication::getProfile()['username'])->first());
        } else {
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $indexPath = $router->pathFor('index');
            $view = new RedirectionView($indexPath, 'Echec de l\'accès au compte !', 'Vous devez être connecté pour accéder à votre compte, vous aller être redirigé vers l\'accueil dans 5 secondes !');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayAccountModification()
    {
        if (Authentication::hasProfile()) {
            $view = new AccountModificationView(AccountModel::where('username', '=', Authentication::getProfile()['username'])->first());
        } else {
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $indexPath = $router->pathFor('index');
            $view = new RedirectionView($indexPath, 'Echec de l\'accès à la modification du compte !', 'Vous devez être connecté pour accéder à la modification de votre compte, vous aller être redirigé vers l\'accueil dans 5 secondes !');
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }
}