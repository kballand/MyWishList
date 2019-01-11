<?php

namespace MyWishList\views;


use MyWishList\models\ItemModel;
use MyWishList\utils\SlimSingleton;

class ItemsDisplayView implements IView
{

    private $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function render()
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        if ($this->items instanceof ItemModel) {
            $description = "";
            if (isset($this->items->description) && !empty($this->items->description)) {
                $description = '<p class="itemDescription"><strong>Description</strong> : ' . $this->items->description . '</p>';
            }
            $url = "";
            if (isset($this->items->url) && !empty($this->items->url)) {
                $url = '<p class="itemUrl"><strong>Site de détail de l\'item</strong> : <a target="_blank" href="' . $this->items->url . '">' . $this->items->url . '</a></p>';
            }
            $reservationState = "Non réservé";
            if ($this->items->reservationState) {
                $reservationState = "Réservé";
            }
            $modifyPath = $router->pathFor('modifyItem', ['no' => $this->items->list->no, 'id' => $this->items->id]) . "?token={$this->items->list->modify_token}";
            $deletePath = $router->pathFor('deleteItem', ['no' => $this->items->list->no, 'id' => $this->items->id]) . "?token={$this->items->list->modify_token}";
            return
                <<< END
<div id="itemContent">
    <h2 class="itemName">{$this->items->name}</h2>
    <img class="itemImg" src="/img/{$this->items->img}" />
    $description
    <p class="itemPrice"><strong>Tarif</strong> : {$this->items->price} €</p>
    $url
    <p class="itemState"><strong>Etat de réservation</strong> : $reservationState</p>
    <span class="actionButtons">
        <a id="deleteButton"  href="$deletePath">Supprimer la l'item</a>
        <a id="modifyButton" href="$modifyPath">Modifier l'item</a>
    </span>
</div>
END;
        } else {
            $itemsContent = "";
            foreach ($this->items as $item) {
                $reservationState = "Non réservé";
                if ($item->reservationState) {
                    $reservationState = "Réservé";
                }
                $itemPath = $router->pathFor('displayItem', ['no' => $item->list->no, 'id' => $item->id]) . "?token={$item->list->modify_token}";
                $itemsContent .=
                    <<< END
<div class="listItem">
    <a target="_blank" href="$itemPath">
        <div class="itemContent">
            <h2 class="itemName">$item->name</h2>
            <img class="itemImg" src="/img/$item->img" />
            <p class="itemState"><strong>Etat de réservation</strong> : $reservationState</p>
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

    public function getRequiredCSS()
    {
        return [];
    }

    public function getRequiredScripts()
    {
        return [];
    }
}