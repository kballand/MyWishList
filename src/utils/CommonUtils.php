<?php

namespace MyWishList\utils;


use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\views\RedirectionView;
use Slim\Http\Request;

class CommonUtils
{

    public static function importScripts($scripts)
    {
        $scriptImport = '';
        if (is_array($scripts)) {
            foreach ($scripts as $script) {
                if (is_string($script)) {
                    $scriptImport .=
                        <<< END
<script type="text/javascript" src="$script"></script>
END;
                }
            }
        } else if (is_string($scripts)) {
            $scriptImport =
                <<< END
<script type="text/javascript" src="$scripts"></script>
END;
        }
        return $scriptImport;
    }


    public static function importCSS($cssFiles)
    {
        $cssImport = '';
        if (is_array($cssFiles)) {
            foreach ($cssFiles as $cssFile) {
                if (is_string($cssFile)) {
                    $cssImport .=
                        <<< END
<link rel="stylesheet" href="$cssFile" />
END;
                }
            }
        } else if (is_string($cssFiles)) {
            $cssImport =
                <<< END
<link rel="stylesheet" href="$cssFiles" />
END;
        }
        return $cssImport;
    }

    public static function canModifyList(Request $request, $no, $errorTitle)
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $indexPath = $router->pathFor('index');
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        $list = ListModel::where('no', '=', $no)->first();
        if (isset($list)) {
            $token = $request->getParam('token');
            if (isset($token)) {
                $modify_token = $list->modify_token;
                if ($token === $modify_token) {
                    return $list;
                } else {
                    $view = new RedirectionView($indexPath, $errorTitle, 'Mauvais token de modification de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($indexPath, $errorTitle, 'Absence du token de modification de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
            }
        } else {
            $view = new RedirectionView($indexPath, $errorTitle, 'Cette liste n\'existe pas, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
        }
        return $view;
    }

    public static function canAccessList(Request $request, $no, $errorTitle)
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $indexPath = $router->pathFor('index');
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        $list = ListModel::where('no', '=', $no)->first();
        if (isset($list)) {
            $token = $request->getParam('token');
            if (isset($token)) {
                $access_token = $list->access_token;
                if ($token === $access_token) {
                    return $list;
                } else {
                    $view = new RedirectionView($indexPath, $errorTitle, 'Mauvais token d\'accès à la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($indexPath, $errorTitle, 'Absence du token d\'accès à la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
            }
        } else {
            $view = new RedirectionView($indexPath, $errorTitle, 'Cette liste n\'existe pas, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
        }
        return $view;
    }

    public static function canModifyItem(Request $request, $no, $id, $errorTitle)
    {
        $canModify = self::canModifyList($request, $no, $errorTitle);
        if ($canModify instanceof ListModel) {
            $list = $canModify;
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            $item = ItemModel::where('id', '=', $id)->first();
            if (isset($item)) {
                if ($item->list->no === $list->no) {
                    return $item;
                } else {
                    $view = new RedirectionView($listPath, $errorTitle, 'Cet item ne fait pas partie de votre liste, vous allez être ridirigé vers celle-ci dans 5 secondes.');
                }
            } else {
                $view = new RedirectionView($listPath, $errorTitle, 'Cet item n\'existe pas, vous allez être ridirigé vers votre liste dans 5 secondes.');
            }
        } else {
            $view = $canModify;
        }
        return $view;
    }
}