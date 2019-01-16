<?php

namespace MyWishList\utils;


use MyWishList\models\ImageModel;
use MyWishList\models\ItemModel;
use MyWishList\models\ListModel;
use MyWishList\views\RedirectionView;
use Slim\Http\Request;

class CommonUtils
{
    /**
     * Méthoder permettant de générer le code html d'import de javascript
     *
     * @param $scripts mixed Ensemble des scripts
     * @return string Le code html d'import du javascript
     */
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

    /**
     * Méthode permettant de générer le code html d'import de css
     *
     * @param $cssFiles mixed Ensemble des fichiers css
     * @return string Le code html d'import du css
     */
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

    /**
     * Méthode permettant de savoir si la liste est accessible ou non à la personne
     *
     * @param Request $request Requête envoyée par le client
     * @param $no int N° de la liste
     * @param $errorTitle string Texte à afficher en cas d'erreur
     * @param $forModificationOnly bool Accès réservé pour ceux qui peuvent modifier uniquement ou non
     * @param bool $modificationGranted Le fait que la modification soit autorisée ou non
     * @return mixed La liste ou alors une vue d'erreur
     */
    public static function canAccessList(Request $request, $no, $errorTitle, $forModificationOnly, &$modificationGranted = false)
    {
        $router = SlimSingleton::getInstance()->getRouter();
        $indexPath = $router->pathFor('index');
        if ($forModificationOnly && Authentication::hasProfile() && Authentication::getProfile()['participant']) {
            $view = new RedirectionView($indexPath, $errorTitle, 'Vous ne pouvez pas modifier une liste avec un compte participant, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
        } else {
            $no = filter_var($no, FILTER_SANITIZE_NUMBER_INT);
            $list = ListModel::where('no', '=', $no)->first();
            if (isset($list)) {
                $token = $request->getParam('token');
                if (isset($token)) {
                    if ($forModificationOnly) {
                        if ($token === $list->modify_token) {
                            if (!self::hasExpired($list)) {
                                $modificationGranted = true;
                                return $list;
                            } else {
                                $listPath = $router->pathFor('displayList', ['no' => $list->no]) . "?token=$list->modify_token";
                                $view = new RedirectionView($listPath, $errorTitle, 'Votre liste a expiré, vous allez être ridirigé vers celle-ci dans 5 secondes.');
                            }
                        } else {
                            $view = new RedirectionView($indexPath, $errorTitle, 'Mauvais token de modification de la liste, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                        }
                    } else {
                        if ($token === $list->modify_token) {
                            if (Authentication::hasProfile() && Authentication::getProfile()['participant']) {
                                $view = new RedirectionView($indexPath, $errorTitle, 'Vous ne pouvez pas accèder à la modification d\'une liste avec un compte participant, vous allez être ridirigé vers l\'accueil dans 5 secondes.');
                            } else {
                                $modificationGranted = true;
                                return $list;
                            }
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
        }
        return $view;
    }

    /**
     * Méthode permettant de savoir si l'accès à un item est possible ou non
     *
     * @param Request $request Requête envoyée par le client
     * @param $no int N° de la liste auquel appartient l'item
     * @param $id int ID de l'item
     * @param $errorTitle string Texte à afficher en cas d'erreur
     * @param $forModificationOnly bool Accès réservé pour ceux qui peuvent modifier uniquement ou non
     * @param bool $modificationGranted Le fait que la modification soit autorisée ou non
     * @return mixed L'item ou une vue d'erreur
     */
    public static function canAccessItem(Request $request, $no, $id, $errorTitle, $forModificationOnly, &$modificationGranted = false)
    {
        $canAccess = self::canAccessList($request, $no, $errorTitle, $forModificationOnly, $modificationGranted);
        if ($canAccess instanceof ListModel) {
            $list = $canAccess;
            $router = SlimSingleton::getInstance()->getRouter();
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
                    if (self::ownList($list)) {
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

    /**
     * Méthode permettant de savoir si une chaîne de caractère commence par un autre
     *
     * @param $haystack string Chaîne à tester
     * @param $needle string Chaîne de commencement
     * @return bool Vrai si la chaîne testée commence par la chaîne donnée, faux sinon
     */
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }

    /**
     * Méthode permettant de savoir si l'utilisateur possède une liste donnée
     *
     * @param ListModel $list La liste que l'utilsateur peut posséder
     * @return bool Vrai si l'utilisateur possède la liste, faux sinon
     */
    public static function ownList(ListModel $list)
    {
        if (!self::hasExpired($list)) {
            if (isset($_COOKIE['mywishlist-' . $list->no])) {
                $hash = $_COOKIE['mywishlist-' . $list->no];
                if (password_verify($list->modify_token, $hash)) {
                    return true;
                }
            } else if (Authentication::hasProfile()) {
                if (Authentication::getProfile()['username'] === $list->owner_name) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Méthode permettant de savoir si la réservation d'un item est possible
     *
     * @param Request $request Requête envoyée par le client
     * @param $no int N° de la liste de l'item
     * @param $id int ID de l'item
     * @param $errorTitle string Texte à afficher en cas d'erreur
     * @return mixed
     */
    public static function canReserveItem(Request $request, $no, $id, $errorTitle)
    {
        $canAccess = CommonUtils::canAccessItem($request, $no, $id, $errorTitle, false, $modificationGranted);
        if ($canAccess instanceof ItemModel) {
            $item = $canAccess;
            $router = SlimSingleton::getInstance()->getRouter();
            $itemPath = $router->pathFor('displayItem', ['no' => $item->list->no, 'id' => $item->id]) . '?token=';
            if ($modificationGranted || self::ownList($item->list)) {
                if ($modificationGranted) {
                    $itemPath .= $item->list->modify_token;
                } else {
                    $itemPath .= $item->list->access_token;
                }
                $view = new RedirectionView($itemPath, $errorTitle, 'Vous ne pouvez pas réserver un item de votre propre liste, vous allez être redirigé vers celui-ci dans 5 secondes.');
            } else {
                $itemPath .= $item->list->access_token;
                if (!isset($item->reservation)) {
                    if (!self::hasExpired($item->list)) {
                        return $item;
                    } else {
                        $view = new RedirectionView($itemPath, $errorTitle, 'La liste de cet item a expiré, vous allez être redirigé vers celui-ci dans 5 secondes.');
                    }
                } else {
                    $view = new RedirectionView($itemPath, $errorTitle, 'Cet item est déjà réservé, vous allez être redirigé vers celui-ci dans 5 secondes.');
                }
            }
        } else {
            $view = $canAccess;
        }
        return $view;
    }

    /**
     * Méthode permettant de savoir si une liste a expiré ou non
     *
     * @param ListModel $list La liste à tester
     * @return bool Vrai si la liste a expiré, faux sinon
     */
    public static function hasExpired(ListModel $list)
    {
        return strtotime($list->expiration) <= strtotime('now');
    }

    /**
     * Méthode permettant de supprimer une liste et ses items dans la base de données
     *
     * @param ListModel $list La liste à supprimer de la base de données
     * @throws \Exception
     */
    public static function deleteList(ListModel $list)
    {
        foreach ($list->items as $item) {
            self::deleteItem($item);
        }
        $list->delete();
    }

    /**
     * Méthode permettant de supprimer un item et ses images inutilisées de la base de données
     *
     * @param ItemModel $item L'item à supprimé de la base de données
     * @throws \Exception
     */
    public static function deleteItem(ItemModel $item)
    {
        $image = $item->image;
        $item->delete();
        self::deleteUnusedImage($image);
    }

    /**
     * Méthode permettant de supprimer une image si elle est inutilisée
     *
     * @param $image ImageModel Image a supprimer si elle est inutilisée
     * @throws \Exception
     */
    public static function deleteUnusedImage($image)
    {
        if (isset($image) && !is_null($image)) {
            if ((!isset($image->items) || count($image->items) === 0) && !$image->local) {
                if ($image->uploaded && file_exists(SlimSingleton::getInstance()->getBaseDir() . 'img/' . $image->basename)) {
                    unlink(SlimSingleton::getInstance()->getBaseDir() . 'img/' . $image->basename);
                }
                $image->delete();
            }
        }
    }
}