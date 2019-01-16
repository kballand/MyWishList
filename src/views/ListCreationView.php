<?php

namespace MyWishList\views;


use DateTime;

class ListCreationView implements IView
{

    public function render()
    {
        $date = new DateTime('tomorrow');
        return
            <<< END
<section class="basicForm">
    <form id="listCreationForm" method="post" novalidate>
        <label for="listTitle">Titre</label>
        <div class="errorDisplayedField">
            <input type="text" name="title" id="listTitle" placeholder="Titre de la liste" class="notEmptyField" aria-invalid="true">
            <div class="displayedError fieldEmptyError" id="listTitleEmptyError">
                <p class="displayedMessage" id="titleEmptyMessage">Le titre de la liste ne peut pas être vide !</p>
            </div>
        </div>
        <label for="listDescription">Description</label>
        <textarea name="description" id="listDescription" rows="10" cols="60" placeholder="Entrez ici la description de votre liste..."></textarea>
        <label for="listExpirationDate">Date d'expiration</label>
        <div class="dateField errorDisplayedField">
            <input type="date" name="expirationDate" id="listExpirationDate" min="{$date->format('Y-m-d')}" class="notEmptyField ulteriorDate" aria-invalid="true">
            <div class="displayedError fieldEmptyError" id="listDateEmptyError">
                <p class="displayedMessage" id="dateEmptyMessage">La date d'expiration de la liste doit être complétée !</p>
            </div>
            <div class="displayedError incorrectDateError" id="incorrectListDateError">
                <p class="displayedMessage" id="incorrectDateMessage">La date d'expiration de la liste doit être ultérieure à la date actuelle !</p>
            </div>
        </div>
        <input type="submit" value="Créer la liste" id="createListButton" class="validateButton">
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
        return ['/js/upload.js', '/js/form.js'];
    }
}