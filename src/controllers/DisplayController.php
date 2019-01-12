<?php

namespace MyWishList\controllers;

use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\utils\CommonUtils;
use MyWishList\views\BasicView;
use MyWishList\views\IndexView;
use MyWishList\views\ItemCreationView;
use MyWishList\views\ItemDisplayView;
use MyWishList\views\ItemModificationView;
use MyWishList\views\ItemReservationView;
use MyWishList\views\ListCreationView;
use MyWishList\views\ListDisplayView;
use MyWishList\views\ListModificationView;
use MyWishList\views\NavBarView;
use MyWishList\views\NotFoundView;
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
        $lists = ListModel::get();
        $view = new ListDisplayView($lists, true);
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayList(Request $request, $no)
    {
        $canAccess = CommonUtils::canAccessList($request, $no, 'Echec de l\'accès à la liste', false, $modificationGranted);
        if ($canAccess instanceof ListModel) {
            $list = $canAccess;
            if ($modificationGranted) {
                $view = new ListDisplayView($list, true);
            } else {
                $view = new ListDisplayView($list, false, CommonUtils::ownList($list));
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
                $view = new ItemDisplayView($item, false, CommonUtils::ownList($item->list));
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
        $view = new RegisterView();
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
        $view = new ListCreationView();
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
}