<?php

namespace MyWishList\views;

class ItemCreationView implements IView {

    public function render() {
        return
            <<< END
<section class="basicForm">
    <form id="itemCreationForm" method="post" novalidate>
        <label for="itemName">Nom</label>
        <div class="errorDisplayedField">
            <input type="text" name="name" id="itemName" placeholder="Nom de l'item" class="notEmptyField" aria-invalid="true">
            <span class="displayedError fieldEmptyError" id="itemNameEmptyError">
                <p class="displayedMessage" id="itemNameEmptyMessage">Le nom de l'item ne peut pas être vide !</p>
            </span>
        </div>
        <label for="itemDescription">Description</label>
        <textarea name="itemDescription" id="itemDescription" rows="10" cols="60" placeholder="Entrez ici la description de votre item... (500 caractères maximum)" maxlength="500"></textarea>
        <label for="itemPrice">Prix</label>
        <div class="errorDisplayedField">
            <input type="number" min="0.01" max="10000.00" step="0.01" id="itemPrice" class="limitedPrice" aria-invalid="true">
            <span class="displayedError incorrectPriceError" id="incorrectItemPriceError">
                <p class="displayedMessage" id="incorrectItemPriceMessage">Le prix de l'item doit être un nombre compris entre 0,01€ et 10000€ !</p>
            </span>
        </div>
        <label for="itemImage">Image de l'item</label>
        <div class="uploadField" id="itemImage">
            <img src="" alt="" id="itemImagePreview" class="imagePreview"/>
            <input type="button" value="Supprimer l'image" class="previewDelete">
            <label for="itemImageUploader" class="previewChanger">Ajouter une image</label>
            <input type="file" accept="image/*" name="itemImageUploader" id="itemImageUploader" class="imageUploader">
        </div>
        <input type="submit" value="Créer l'item" id="createItemButton" class="createButton">
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