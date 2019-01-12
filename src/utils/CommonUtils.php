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

    public static function canAccessList(Request $request, $no, $errorTitle, $forModificationOnly, &$modificationGranted = false)
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $indexPath = $router->pathFor('index');
        $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
        $list = ListModel::where('no', '=', $no)->first();
        if (isset($list)) {
            $token = $request->getParam('token');
            if (isset($token)) {
                if ($forModificationOnly) {
                    if ($token === $list->modify_token) {
                        $modificationGranted = true;
                        return $list;
                    } else {
                        $view = new RedirectionView($indexPath, $errorTitle, 'Mauvais token de modification de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                    }
                } else {
                    if ($token === $list->modify_token) {
                        $modificationGranted = true;
                        return $list;
                    } else if ($token === $list->access_token) {
                        $modificationGranted = false;
                        return $list;
                    } else {
                        $view = new RedirectionView($indexPath, $errorTitle, 'Mauvais token d\'accès à la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                    }
                }
            } else {
                if ($forModificationOnly) {
                    $view = new RedirectionView($indexPath, $errorTitle, 'Absence du token de modification de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                } else {
                    $view = new RedirectionView($indexPath, $errorTitle, 'Absence du token d\'accès à la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                }
            }
        } else {
            $view = new RedirectionView($indexPath, $errorTitle, 'Cette liste n\'existe pas, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
        }
        return $view;
    }

    public static function canAccessItem(Request $request, $no, $id, $errorTitle, $forModificationOnly, &$modificationGranted = false)
    {
        $canAccess = self::canAccessList($request, $no, $errorTitle, $forModificationOnly, $modificationGranted);
        if ($canAccess instanceof ListModel) {
            $list = $canAccess;
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            if ($forModificationOnly || $modificationGranted) {
                $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
            } else {
                $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->access_token";
            }
            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            $item = ItemModel::where('id', '=', $id)->first();
            if (isset($item)) {
                if ($item->list->no === $list->no) {
                    if ($forModificationOnly && isset($item->reservation)) {
                        $view = new RedirectionView($listPath, $errorTitle, 'Cet item est réservé, vous allez être ridirigé vers votre liste dans 5 secondes.');
                    } else {
                        return $item;
                    }
                } else {
                    if ($forModificationOnly) {
                        $view = new RedirectionView($listPath, $errorTitle, 'Cet item ne fait pas partie de votre liste, vous allez être ridirigé vers celle-ci dans 5 secondes.');
                    } else {
                        $view = new RedirectionView($listPath, $errorTitle, 'Cet item ne fait pas partie de cette liste, vous allez être ridirigé vers celle-ci dans 5 secondes.');
                    }
                }
            } else {
                $view = new RedirectionView($listPath, $errorTitle, 'Cet item n\'existe pas, vous allez être ridirigé vers votre liste dans 5 secondes.');
            }
        } else {
            $view = $canAccess;
        }
        return $view;
    }

    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }

    public static function ownList($list)
    {
        if (strtotime($list->expiration) > strtotime('now')) {
            if (isset($_COOKIE['mywishlist-' . $list->no])) {
                $hash = $_COOKIE['mywishlist-' . $list->no];
                if (password_verify($list->modify_token, $hash)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function canReserveItem(Request $request, $no, $id, $errorTitle)
    {
        $canAccess = CommonUtils::canAccessItem($request, $no, $id, $errorTitle, false, $modificationGranted);
        if ($canAccess instanceof ItemModel) {
            $item = $canAccess;
            $router = SlimSingleton::getInstance()->getContainer()->get('router');
            $itemPath = $router->pathFor('displayItem', ['no' => $item->list->no, 'id' => $item->id]) . '?token=';
            if ($modificationGranted || self::ownList($item->list)) {
                if ($modificationGranted) {
                    $itemPath .= $item->list->modify_token;
                } else {
                    $itemPath .= $item->list->access_token;
                }
                $view = new RedirectionView($itemPath, $errorTitle, 'Vous ne pouvez pas réserver un item de votre propre liste, vous allez être redirigé vers celui-ci dans 5 secondes.');
            } else {
                if (!isset($item->reservation)) {
                    return $item;
                } else {
                    $itemPath .= $item->list->access_token;
                    $view = new RedirectionView($itemPath, $errorTitle, 'Cet item est déjà réservé, vous allez être redirigé vers celui-ci dans 5 secondes.');
                }
            }
        } else {
            $view = $canAccess;
        }
        return $view;
    }
}