<?php
/**
 * Created by PhpStorm.
 * User: Killian
 * Date: 04/12/2018
 * Time: 21:48
 */

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
            $itemsContent = "";
            foreach($this->lists->items as $item) {
                $itemsContent .=
<<< END
<a class="listItem" href="{$router->pathFor('item', ['id' => $item->id])}">
    <h2 class="itemName">$item->nom</h2>
    <img class="itemImg" src="/img/$item->img" />
    <p class="itemId"><strong>ID</strong> : $item->id</p>
    <p class="itemDescription"><strong>Description</strong> : $item->descr</p>
    <p class="itemPrice"><strong>Tarif</strong> : $item->tarif €</p>
</a>
END;
            }
            return
<<< END

<div id="listContent">
    <h2 class="listTitle">{$this->lists->titre}</h2>
    <p class="listId"><strong>ID</strong> : {$this->lists->no}</p>
    <p class="listDescription"><strong>Description</strong>  : {$this->lists->description}</p>
    <p class="listExpiration"><strong>Date d'expiration</strong> : {$this->lists->expiration}</p>
    <div id="itemsPart">
        <h3 class="listItemTitle"><strong>Items de la liste :</strong></h3>
        <div id="listItems">
            $itemsContent
        </div>
    </div>

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
}