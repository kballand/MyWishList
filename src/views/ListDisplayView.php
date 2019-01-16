<?php

namespace MyWishList\views;


use MyWishList\models\ListModel;
use MyWishList\utils\CommonUtils;
use MyWishList\utils\SlimSingleton;

/**
 * Vue correspondant à l'affichage de listes
 *
 * @package MyWishList\views
 */
class ListDisplayView implements IView
{
    /**
     * @var mixed Listes à afficher
     */
    private $lists;
    /**
     * @var bool Dans le cadre de modifications ou  non
     */
    private $forModification;

    /**
     * Constructeur de la vue
     *
     * @param $lists mixed Listes à afficher
     * @param $forModification bool Dans le cadre de modifications ou non
     */
    public function __construct($lists, $forModification)
    {
        $this->lists = $lists;
        $this->forModification = $forModification;
    }

    public function render()
    {
        $router = SlimSingleton::getInstance()->getRouter();
        if ($this->lists instanceof ListModel) {
            $itemsContent = "";
            if (isset($this->lists->items) && count($this->lists->items) > 0) {
                $itemsView = new ItemDisplayView($this->lists->items, $this->forModification);
                $itemsContent = $itemsView->render();
            }
            $description = "";
            if (isset($this->lists->description) && !empty($this->lists->description)) {
                $description = '<p class="listDescription"><strong>Description</strong>  : ' . $this->lists->description . '</p>';
            }
            $actionButtons = "";
            if ($this->forModification && !CommonUtils::hasExpired($this->lists)) {
                $modifyPath = $router->pathFor('modifyList', ['no' => $this->lists->no]) . "?token={$this->lists->modify_token}";
                $deletePath = $router->pathFor('deleteList', ['no' => $this->lists->no]) . "?token={$this->lists->modify_token}";
                $addItemPath = $router->pathFor('addItem', ['no' => $this->lists->no]) . "?token={$this->lists->modify_token}";
                if (isset($this->lists->access_token)) {
                    $accessPath = $_SERVER['HTTP_HOST'] . $router->pathFor('displayList', ['no' => $this->lists->no]) . "?token=" . $this->lists->access_token;
                    $optionalButtons =
                        <<< END
<button id="shareButton" class="popupOpener">Voir le lien de partage</button>
<div class="popup">
    <div class="popupContent">
        <div>
            <input type="url" value="$accessPath" id="accessURL" class="copiedText" readonly>
            <div class="actionButtons">
                <button class="textCopier">Copier sur le presse-papier</button>
                <button class="popupCloser">Fermer</button>
            </div>
        </div>
    </div>
</div>
END;
                    if (!$this->lists->public) {
                        $publicizeListPath = $router->pathFor('publicizeList', ['no' => $this->lists->no]) . "?token={$this->lists->modify_token}";
                        $optionalButtons .=
                            <<< END
<a id="publicizeListButton" href="$publicizeListPath">Rendre publique</a>
END;
                    } else {
                        $privatizeListPath = $router->pathFor('privatizeList', ['no' => $this->lists->no]) . "?token={$this->lists->modify_token}";
                        $optionalButtons .=
                            <<< END
<a id="privatizeListButton" href="$privatizeListPath">Rendre privée</a>
END;
                    }
                } else {
                    $sharePath = $router->pathFor('shareList', ['no' => $this->lists->no]) . "?token=" . $this->lists->modify_token;
                    $optionalButtons =
                        <<< END
<a id="shareListButton" href="$sharePath">Partager la liste</a>
END;
                }
                $actionButtons =
                    <<< END
<div class="actionButtons">
    <a id="deleteButton"  href="$deletePath">Supprimer la liste</a>
    <a id="modifyButton" href="$modifyPath">Modifier la liste</a>
    <a id="addItemButton" href="$addItemPath">Ajouter un item</a>
    $optionalButtons
</div> 
END;
            }
            $comments = "";
            foreach ($this->lists->comments->reverse() as $comment) {
                $comments .=
                    <<< END
<p class="listComment">$comment->comment</p>
END;
            }
            $commentsPart =
                <<< END
<div id="listCommentsPart">
    $comments
</div>
END;
            $commentForm = "";
            if (!$this->forModification && !CommonUtils::ownList($this->lists) && !CommonUtils::hasExpired($this->lists)) {

                $commentForm =
                    <<< END
<div class="basicForm">
    <form id="listCommentForm" method="post" novalidate>
        <label for="listCommentMessage">Commentaire</label>
        <div class="errorDisplayedField">
            <textarea name="comment" id="listCommentMessage" class="notEmptyField" rows="10" cols="60" placeholder="Entrez ici un commentaire à propos de cette liste de souhaits..." aria-invalid="true"></textarea>
            <div class="displayedError fieldEmptyError" id="listCommentMessageEmptyError">
                <p class="displayedMessage" id="listCommentMessageEmptyMessage">Votre commentaire ne peut être vide !</p>
            </div>
        </div>
        <input type="submit" value="Envoyer ce commentaire" id="commentListButton" class="validateButton">
    </form>
</div>
END;
            }
            return
                <<< END
<div id="listContent">
    <h2 class="listTitle">{$this->lists->title}</h2>
    $description
    <p class="listExpiration"><strong>Date d'expiration</strong> : {$this->lists->expiration}</p>
    $itemsContent
    $actionButtons
</div>
$commentForm
$commentsPart
END;
        } else {
            $sectionContent = "";
            foreach ($this->lists as $list) {
                $listPath = $router->pathFor('displayList', ['no' => $list->no]) . '?token=';
                if ($this->forModification) {
                    $listPath .= $list->modify_token;
                } else {
                    $listPath .= $list->access_token;
                }
                $sectionContent .=
                    <<<END
    <a class="listArticle" href="$listPath">
        <h2 class="listTitle">$list->title</h2>
    </a>
END;
            }
            return
                <<< END
<section id="listsSection">
    $sectionContent
</section>
END;
        }
    }

    public function getRequiredCSS()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return [$basePath . 'css/popup.css', $basePath . 'css/form.css'];
    }

    public function getRequiredScripts()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return [$basePath . 'js/popup.js', $basePath . 'js/form.js'];
    }
}