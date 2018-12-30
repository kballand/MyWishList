<?php
/**
 * Created by PhpStorm.
 * User: Killian
 * Date: 25/12/2018
 * Time: 14:35
 */

namespace MyWishList\views;


class ListCreationView implements IView {

    public function render() {
        $date = getdate();
        $year = $date['year'];
        $month = $date['mon'];
        $day = $date['mday'];
        return
<<< END
<form id="listCreationForm" method="post">
    <label for="listTitle">Titre</label>
    <input type="text" name="title" id="listTitle">
    <label for="listDescription">Description</label>
    <textarea name="description" id="listDescription" rows="10" cols="60" placeholder="Entrez ici la description de votre liste... (255 caractères maximum)" maxlength="255" style="resize: none"></textarea>
    <label for="listExpirationDate">Date d'expiration</label>
    <input type="date" name="expirationDate" id="listExpirationDate" min="$year-$month-$day">
    <span>
        <label for="listImage">Ajouter une image</label>
        <input type="file" accept="image/*" name="image" id="listImage">
    </span>
</form>
END;
    }

    public function getRequiredCSS() {
        return [];
    }

    public function getRequiredScripts() {
        return [];
    }
}