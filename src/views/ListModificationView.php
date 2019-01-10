<?php

namespace MyWishList\views;


use DateTime;

class ListModificationView implements IView {
    private $list;

    public function __construct($list) {
        $this->list = $list;
    }

    public function render() {
        $date = new DateTime('now');
        return
<<< END
<section class="basicForm">
    <form id="listCreationForm" method="post">
        <label for="listTitle">Titre</label>
        <div class="errorDisplayedField">
            <input type="text" name="title" id="listTitle" placeholder="Titre de la liste" class="notEmptyField" value="{$this->list->title}" aria-invalid="true">
            <span class="displayedError fieldEmptyError" id="listTitleEmptyError">
                <p class="displayedMessage" id="titleEmptyMessage">Le titre de la liste ne peut pas être vide !</p>
            </span>
        </div>
        <label for="listDescription">Description</label>
        <textarea name="description" id="listDescription" rows="10" cols="60" placeholder="Entrez ici la description de votre liste... (500 caractères maximum)" maxlength="500">{$this->list->description}</textarea>
        <label for="listExpiration">Date d'expiration</label>
        <div class="errorDisplayedField">
            <input type="date" name="expirationDate" id="listExpirationDate" min="{$date->format('Y-m-d')}" class="notEmptyField ulteriorDate" aria-invalid="true" value="{$this->list->expiration}">
            <span class="displayedError fieldEmptyError" id="listDateEmptyError">
                <p class="displayedMessage" id="dateEmptyMessage">La date d'expiration de la liste doit être complétée !</p>
            </span>
            <span class="displayedError incorrectDateError" id="incorrectListDateError">
                <p class="displayedMessage" id="incorrectDateMessage">La date d'expiration de la liste doit être ultérieure à la date actuelle !</p>
            </span>
        </div>
        <label for="listImage">Image de la liste</label>
        <div class="uploadField" id="listImage">
            <img src="" alt="" id="listImagePreview" class="imagePreview"/>
            <input type="button" value="Supprimer l'image" class="previewDelete">
            <label for="listImageUploader" class="previewChanger">Ajouter une image</label>
            <input type="file" accept="image/*" name="imageName" id="listImageUploader" class="imageUploader">
        </div>
        <input type="submit" value="Modifier la liste" id="createListButton">
    </form>
</section>
END;
    }

    public function getRequiredCSS() {
        return ['/css/form.css'];
    }

    public function getRequiredScripts() {
        return ['/js/upload.js', '/js/form.js'];
    }
}