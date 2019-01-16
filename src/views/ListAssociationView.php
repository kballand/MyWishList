<?php

namespace MyWishList\views;


use MyWishList\utils\SlimSingleton;

/**
 * Vue correspondant à l'affichage de l'association d'une liste à un comtpe
 *
 * @package MyWishList\views
 */
class ListAssociationView implements IView
{
    public function render()
    {
        return
            <<< END
<section class="basicForm">
    <form id="listAssociationForm" method="post" novalidate>
        <label for="listAssociationID">N° de la liste</label>
        <div class="errorDisplayedField">
            <input min="0" type="number" name="no" id="listAssociationID" placeholder="N°" class="notEmptyField" aria-invalid="true">
            <div class="displayedError fieldEmptyError" id="listAssociationIDEmptyError">
                <p class="displayedMessage" id="listAssociationIDEmptyMessage">Le n° de la liste ne peut pas être vide !</p>
            </div>
        </div>
        <label for="listAssociationToken">Token de modification</label>
        <div class="errorDisplayedField">
            <input type="text" name="token" id="listAssociationToken" placeholder="Token de modification" class="notEmptyField" aria-invalid="true">
            <div class="displayedError fieldEmptyError" id="listAssociationTokenEmptyError">
                <p class="displayedMessage" id="listAssociationTokenEmptyMessage">Le token de modification de la liste ne peut pas être vide !</p>
            </div>
        </div>
        <input type="submit" value="Associer" class="validateButton" id="listAssociationButton">
    </form>
</section>
END;
    }

    public function getRequiredCSS()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return [$basePath . 'css/form.css'];
    }

    public function getRequiredScripts()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return [$basePath . 'js/form.js'];
    }
}