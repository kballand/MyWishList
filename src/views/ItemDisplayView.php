<?php

namespace MyWishList\views;


use MyWishList\models\ItemModel;
use MyWishList\utils\CommonUtils;
use MyWishList\utils\SlimSingleton;

class ItemDisplayView implements IView
{

    private $items;
    private $forModification;

    public function __construct($items, $forModification)
    {
        $this->items = $items;
        $this->forModification = $forModification;
    }

    public function render()
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        if ($this->items instanceof ItemModel) {
            $img = "";
            if (isset($this->items->img) && !empty($this->items->img)) {
                $img = '<img class="itemImg" src="/img/' . $this->items->img . '" />';
            }
            $description = "";
            if (isset($this->items->description) && !empty($this->items->description)) {
                $description = '<p class="itemDescription"><strong>Description</strong> : ' . $this->items->description . '</p>';
            }
            $url = "";
            if (isset($this->items->url) && !empty($this->items->url)) {
                $url = '<p class="itemUrl"><strong>Site de détail de l\'item</strong> : <a target="_blank" href="' . $this->items->url . '">' . $this->items->url . '</a></p>';
            }
            $reservationState = "Non réservé";
            $reservationInformations = "";
            if (isset($this->items->reservation)) {
                $reservationState = "Réservé";
                if (!$this->forModification && !CommonUtils::ownList($this->items->list)) {
                    $reservationInformations =
                        <<< END
<p class="itemReservationParticipant"><strong>Nom du participant</strong> : {$this->items->reservation->participant}</p>
END;
                } else if (CommonUtils::hasExpired($this->items->list)) {
                    $reservationInformations =
                        <<< END
<p class="itemReservationParticipant"><strong>Nom du participant</strong> : {$this->items->reservation->participant}</p>
<p class="itemReservationMessage"><strong>Message</strong> :<br/>{$this->items->reservation->message}</p>
END;
                }
            }
            $actionButtons = "";
            if(!CommonUtils::hasExpired($this->items->list)) {
                if ($this->forModification) {
                    $modifyPath = $router->pathFor('modifyItem', ['no' => $this->items->list->no, 'id' => $this->items->id]) . "?token={$this->items->list->modify_token}";
                    $deletePath = $router->pathFor('deleteItem', ['no' => $this->items->list->no, 'id' => $this->items->id]) . "?token={$this->items->list->modify_token}";
                    $actionButtons =
                        <<< END
<span class="actionButtons">
    <a id="deleteButton"  href="$deletePath">Supprimer l'item</a>
    <a id="modifyButton" href="$modifyPath">Modifier l'item</a>
</span>
END;
                } else if (!isset($this->items->reservation) && !CommonUtils::ownList($this->items->list)) {
                    $reservePath = $router->pathFor('reserveItem', ['no' => $this->items->list->no, 'id' => $this->items->id]) . "?token={$this->items->list->access_token}";
                    $actionButtons =
                        <<< END
<span class="actionButtons">
    <a id="reserveButton"  href="$reservePath">Réserver</a>
</span>
END;
                }
            }
            return
                <<< END
<div id="itemContent">
    <h2 class="itemName">{$this->items->name}</h2>
    $img
    $description
    <p class="itemPrice"><strong>Tarif</strong> : {$this->items->price} €</p>
    $url
    <p class="itemReservationState"><strong>Etat de réservation</strong> : $reservationState</p>
    $reservationInformations
    $actionButtons
</div>
END;
        } else {
            $itemsContent = "";
            $itemActions = "";
            if ($this->forModification) {
                $itemActions =
                    <<< END
<div class="itemActions">
    <a class="itemAction itemModify">&#9998</a>
    <a class="itemAction itemDelete">&#10006</a>
</div>
END;
            }
            foreach ($this->items as $item) {
                $img = "";
                if (isset($item->img) && !empty($item->img)) {
                    $img = '<img class="itemImg" src="/img/' . $item->img . '" />';
                }
                $reservationState = "Non réservé";
                if (isset($item->reservation)) {
                    $reservationState = "Réservé";
                }
                $itemPath = $router->pathFor('displayItem', ['no' => $item->list->no, 'id' => $item->id]) . '?token=';
                if ($this->forModification) {
                    $itemPath .= $item->list->modify_token;
                } else {
                    $itemPath .= $item->list->access_token;
                }
                $itemsContent .=
                    <<< END
<div id="itemsSection">
    <a target="_blank" href="$itemPath">
        <div class="itemContent">
            <h2 class="itemName">$item->name</h2>
            $img
            <p class="itemState"><strong>Etat de réservation</strong> : $reservationState</p>
        </div>
    </a>
    $itemActions
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