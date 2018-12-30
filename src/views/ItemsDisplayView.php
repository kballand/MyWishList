<?php

namespace MyWishList\views;


use MyWishList\models\ItemModel;
use MyWishList\utils\SlimSingleton;

class ItemsDisplayView implements IView {

    private $items;

    public function __construct($items) {
        $this->items = $items;
    }

    public function render() {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        if($this->items instanceof ItemModel) {
            return
<<< END
<div id="itemContent">
    <h2 class="itemName">{$this->items->nom}</h2>
    <img class="itemImg" src="/img/{$this->items->img}" />
    <p class="itemId"><strong>ID</strong> : {$this->items->id}</p>
    <p class="itemDescription"><strong>Description</strong> : {$this->items->descr}</p>
    <p class="itemPrice"><strong>Tarif</strong> : {$this->items->tarif} €</p>
</div>
END;
        } else {
            $itemsContent = "";
            foreach ($this->items as $item) {
                $itemsContent .=
                    <<< END
<div class="listItem">
    <a href="{$router->pathFor('item', ['id' => $item->id])}">
        <div class="itemContent">
            <h2 class="itemName">$item->nom</h2>
            <img class="itemImg" src="/img/$item->img" />
            <p class="itemId"><strong>ID</strong> : $item->id</p>
            <p class="itemDescription"><strong>Description</strong> : $item->descr</p>
            <p class="itemPrice"><strong>Tarif</strong> : $item->tarif €</p>
        </div>
    </a>
    <div class="itemActions">
        <a class="itemAction itemModify">&#9998</a>
        <a class="itemAction itemDelete">&#10006</a>
    </div>
</div>
END;
            }
            return
<<< END
<div id="itemsPart">
    <h3 class="listItemTitle"><strong>Items de la liste :</strong></h3>
    <div id="listItems">
        $itemsContent
    </div>
</div>
END;
        }
    }

    public function getRequiredCSS() {
        return [];
    }

    public function getRequiredScripts() {
        return [];
    }
}