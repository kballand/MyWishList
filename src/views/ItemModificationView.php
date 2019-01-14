<?php

namespace MyWishList\views;


use MyWishList\models\ItemModel;

class ItemModificationView implements IView
{
    private $item;

    public function __construct(ItemModel $item)
    {
        $this->item = $item;
    }

    public function render()
    {
        $imageDisplay = "";
        $buttonsDisplay = "";
        $otherDisplay = "";
        $buttonText = "Ajouter une image";
        $src = "";
        $hotlink = "";
        if(isset($this->item->image)) {
            $imageDisplay = 'style="display: block"';
            $buttonsDisplay = 'style="display: inline-block"';
            $otherDisplay = 'style="display: none"';
            $buttonText = "Modifier l'image";
            $image = $this->item->image;
            if($image->uploaded) {
                $src = '/img/' . $image->basename;
            } else {
                $src = $image->basename;
            }
            $hotlink = $image->basename;
        }
        return
            <<< END
<section class="basicForm">
    <form id="itemCreationForm" method="post" enctype="multipart/form-data" novalidate>
        <label for="itemName">Nom</label>
        <div class="errorDisplayedField">
            <input type="text" name="name" id="itemName" placeholder="Nom de l'item" class="notEmptyField" aria-invalid="true" value="{$this->item->name}">
            <span class="displayedError fieldEmptyError" id="itemNameEmptyError">
                <p class="displayedMessage" id="itemNameEmptyMessage">Le nom de l'item ne peut pas être vide !</p>
            </span>
        </div>
        <label for="itemDescription">Description</label>
        <textarea name="description" id="itemDescription" rows="10" cols="60" placeholder="Entrez ici la description de votre item... (500 caractères maximum)" maxlength="500">{$this->item->description}</textarea>
        <label for="itemPrice">Prix</label>
        <div class="errorDisplayedField">
            <input type="number" min="0.01" max="999.99" step="0.01" name="price" id="itemPrice" class="limitedPrice" aria-invalid="true" value="{$this->item->price}">
            <span class="displayedError incorrectPriceError" id="incorrectItemPriceError">
                <p class="displayedMessage" id="incorrectItemPriceMessage">Le prix de l'item doit être un nombre compris entre 0,01€ et 999,99€ !</p>
            </span>
        </div>
        <label for="itemImage">Image de l'item</label>
        <div class="uploadField" id="itemImage">
            <img src="$src" alt="" id="itemImagePreview" class="imagePreview" $imageDisplay>
            <input type="button" value="Supprimer l'image" class="previewDelete" $buttonsDisplay>
            <label class="popupOpener previewChanger">$buttonText</label>
            <div class="popup">
                <div class="popupContent">
                    <div class="imageHotlinkField">
                        <label for="itemImageHotlink">URL de l'image</label>
                        <input type="url" name="imageHotlink" id="itemImageHotlink" class="imageHotlink" value="$hotlink">
                    </div>
                    <div class="imageUploaderField" $otherDisplay>
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
        <input type="text" name="website" id="itemWebsite" placeholder="URL du site" value="{$this->item->url}">
        <input type="submit" value="Modifier l'item" id="createItemButton" class="validateButton">
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