<?php

namespace MyWishList\views;


use MyWishList\models\AccountModel;

class AccountModificationView implements IView
{
    private $account;

    public function __construct(AccountModel $account)
    {
        $this->account = $account;
    }

    public function render()
    {
        return
            <<< END
<section class="basicForm">
    <form id="accountModificationForm" method="post" novalidate>
        <label for="accountModificationFirstName">Prénom</label>
        <div class="errorDisplayedField">
            <input type="text" name="firstName" id="accountModificationFirstName" placeholder="Votre prénom" class="notEmptyField" aria-invalid="true">
            <div class="displayedError fieldEmptyError" id="accountFirstNameEmptyError">
                <p class="displayedMessage" id="firstNameEmptyMessage">Votre prénom ne peut être laissé vide !</p>
            </div>
        </div>
        <label for="accountModificationLastName">Nom</label>
        <div class="errorDisplayedField">
            <input type="text" name="lastName" id="accountModificationLastName" placeholder="Votre nom" class="notEmptyField" aria-invalid="true">
            <div class="displayedError fieldEmptyError" id="accountModificationLastNameEmptyError">
                <p class="displayedMessage" id="lastNameEmptyMessage">Votre nom ne peut être laissé vide !</p>
            </div>
        </div>
        <label for="accountModificationEmail">E-mail</label>
        <div class="errorDisplayedField">
            <input type="email" name="email" id="accountModificationEmail" placeholder="Votre email" class="emailField" aria-invalid="true">
            <div class="displayedError emailInvalidError" id="accountModificationEmailInvalidError">
                <p class="displayedMessage" id="accountModificationEmailInvalidMessage">Vous devez entrer un email valide !</p>
            </div>
        </div>
        <label for="accountModificationPassword">Mot de passe</label>
        <div class="errorDisplayedField">
            <input type="text" style="display:none;">
            <input type="password" style="display:none;">
            <input type="password" name="password" id="accountModificationPassword" placeholder="Votre mot de passe" class="passwordField" aria-invalid="true">
            <div class="displayedError passwordInvalidError" id="accountModificationPasswordInvalidError">
                <p class="displayedMessage" id="passwordModificationPasswordInvalidMessage">Mot de passe invalide !</p>
            </div>
        </div>
        <input type="submit" class="validateButton" value="Valider les modifications">
    </form>
</section>
END;
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