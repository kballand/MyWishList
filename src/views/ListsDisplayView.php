<?php

namespace MyWishList\views;


use MyWishList\models\ListModel;
use MyWishList\utils\SlimSingleton;

class ListsDisplayView implements IView {
    private $lists;

    public function __construct($lists) {
        $this->lists = $lists;
    }

    public function render() {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        if($this->lists instanceof ListModel) {
            $itemsView = new ItemsDisplayView($this->lists->items);
            $itemsContent = $itemsView->render();
            return
<<< END
<div id="listContent">
    <h2 class="listTitle">{$this->lists->titre}</h2>
    <p class="listId"><strong>ID</strong> : {$this->lists->no}</p>
    <p class="listDescription"><strong>Description</strong>  : {$this->lists->description}</p>
    <p class="listExpiration"><strong>Date d'expiration</strong> : {$this->lists->expiration}</p>
    $itemsContent
</div>
END;
        } else {
            $sectionContent = "";
            foreach($this->lists as $list) {
                $sectionContent .=
<<<END
    <a class="listArticle" href="{$router->pathFor('list', ['no' => $list->no])}">
        <h2 class="listTitle">$list->titre</h2>
        <p class="listId"><strong>ID</strong> : $list->no</p>
        <p class="listDescription"><strong>Description</strong>  : $list->description</p>
        <p class="listExpiration"><strong>Date d'expiration</strong> : $list->expiration</p>
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

    public function getRequiredCSS() {
        return [];
    }

    public function getRequiredScripts()
    {
        return [];
    }
}