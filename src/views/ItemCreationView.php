<?php

namespace MyWishList\views;

class ItemCreationView implements IView
{

    public function __construct()
    {
    }

    public function render()
    {
        return
            <<< END
<section class="basicForm">
    <form id="itemCreationForm" method="post" enctype="multipart/form-data" novalidate>
        <label for="itemName">Nom</label>
        <div class="errorDisplayedField">
            <input type="text" name="name" id="itemName" placeholder="Nom de l'item" class="notEmptyField" aria-invalid="true">
            <div class="displayedError fieldEmptyError" id="itemNameEmptyError">
                <p class="displayedMessage" id="itemNameEmptyMessage">Le nom de l'item ne peut pas être vide !</p>
            </div>
        </div>
        <label for="itemDescription">Description</label>
        <textarea name="description" id="itemDescription" rows="10" cols="60" placeholder="Entrez ici la description de votre item..."></textarea>
        <label for="itemPrice">Prix</label>
        <div class="errorDisplayedField">
            <input type="number" min="0.01" max="999.99" step="0.01" name="price" id="itemPrice" class="limitedPrice" aria-invalid="true">
            <div class="displayedError incorrectPriceError" id="incorrectItemPriceError">
                <p class="displayedMessage" id="incorrectItemPriceMessage">Le prix de l'item doit être un nombre compris entre 0,01€ et 10000€ !</p>
            </div>
        </div>
        <label for="itemImage">Image de l'item</label>
        <div class="uploadField" id="itemImage">
            <img src="" alt="" id="itemImagePreview" class="imagePreview"/>
            <input type="button" value="Supprimer l'image" class="previewDelete">
            <label class="popupOpener previewChanger">Ajouter une image</label>
            <div class="popup">
                <div class="popupContent">
                    <div class="imageHotlinkField">
                        <label for="itemImageHotlink">URL de l'image</label>
                        <input type="url" name="imageHotlink" id="itemImageHotlink" class="imageHotlink">
                    </div>
                    <div class="imageUploaderField">
                        <label>Uploadez votre image</label>
                        <input type="file" accept="image/*" name="imageUpload" id="itemImageUploader" class="imageUploader">
                    </div>
                    <span class="actionButtons">
                        <button class="popupCloser uploadCloser">Fermer</button>
                    </span>
                </div>
            </div>
        </div>
        <label for="itemWebsite">Site détaillant le produit</label>
        <input type="text" name="website" id="itemWebsite" placeholder="URL du site">
        <input type="submit" value="Créer l'item" id="createItemButton" class="validateButton">
    </form>
</section>
END;
    }

    public function getRequiredCSS()
    {
        return ['/css/form.css', '/css/popup.css'];
    }

    public function getRequiredScripts()
    {
        return ['/js/upload.js', '/js/form.js', '/js/popup.js'];
    }
}