<?php

namespace MyWishList\views;


use MyWishList\models\ItemModel;
use MyWishList\utils\Authentication;

class ItemReservationView implements IView
{
    private $item;

    public function __construct(ItemModel $item)
    {
        $this->item = $item;
    }


    public function render()
    {
        $nameField = "";
        if (!Authentication::hasProfile()) {
            $reservationName = "";
            if (isset($_SESSION['participantName'])) {
                $reservationName = $_SESSION['participantName'];
            }
            $nameField =
                <<< END
<label for="reservationName">Nom</label>
<div class="errorDisplayedField">
    <input type="text" name="name" id="reservationName" placeholder="Nom de participation" class="notEmptyField" value="$reservationName" aria-invalid="true">
    <span class="displayedError fieldEmptyError" id="reservationNameEmptyError">
        <p class="displayedMessage" id="reservationNameEmptyMessage">Votre nom de participation ne peut pas être vide !</p>
    </span>
</div>
END;
        }

        return
            <<< END
<section class="basicForm">
    <form id="itemReservationForm" method="post" novalidate>
        $nameField
        <label for="reservationMessage">Message</label>
        <textarea name="message" id="reservationMessage" rows="10" cols="60" placeholder="Entrez ici un message destiné au créateur de la liste de souhaits..."></textarea>
        <input type="submit" value="Réserver l'item" id="reserveItemButton" class="validateButton">
    </form>
</section>
END;
    }

    public function getRequiredCSS()
    {
        return ['/css/form.css'];
    }

    public function getRequiredScripts()
    {
        return ['/js/form.js'];
    }
}