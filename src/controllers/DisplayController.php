<?php

namespace MyWishList\controllers;

use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\utils\CommonUtils;
use MyWishList\utils\SlimSingleton;
use MyWishList\views\BasicView;
use MyWishList\views\IndexView;
use MyWishList\views\ItemCreationView;
use MyWishList\views\ItemModificationView;
use MyWishList\views\ItemsDisplayView;
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
        $view = new ListDisplayView($lists);
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayList(Request $request, $no)
    {
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        $list = ListModel::where('no', '=', $no)->first();
        $view = new ListDisplayView($list);
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }

    public function displayItem(Request $request, $no, $id)
    {
        $canModify = CommonUtils::canModifyList($request, $no, 'Echec de l\'accès à l\'item !');
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
            $exists = CommonUtils::itemExists($id, 'Echec de l\'accès à l\'item !', $listPath);
            if ($exists instanceof ItemModel) {
                $item = $exists;
                $view = new ItemsDisplayView($item);
            } else {
                $view = $exists;
            }
        } else {
            $view = $canModify;
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
        $canModify = CommonUtils::canModifyList($request, $no, 'Echec de l\'accès à la modification de la liste !');
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
        $canModify = CommonUtils::canModifyList($request, $no, 'Echec de l\'ajout d\'un item à la liste !');
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
        $canModify = CommonUtils::canModifyList($request, $no, 'Echec de l\'accès à la modification de l\'item !');
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
            $exists = CommonUtils::itemExists($id, 'Echec de l\'accès à l\'item !', $listPath);
            if ($exists instanceof ItemModel) {
                $item = $exists;
                $view = new ItemModificationView($item);
            } else {
                $view = $exists;
            }
        } else {
            $view = $canModify;
        }
        $view = new NavBarView($view);
        $view = new BasicView($view);
        return $view->render();
    }
}