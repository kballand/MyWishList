<?php

namespace MyWishList\views;


class RegisterView implements IView
{
    public function render()
    {
        return
            <<< END
<section class="basicForm">
    <form id="registerForm" class="multipartForm" method="post" novalidate>
        <div class="registerPart">
            <label for="registerFirstName">Prénom</label>
            <div class="errorDisplayedField">
                <input type="text" name="firstName" id="registerFirstName" placeholder="Votre prénom" class="notEmptyField" aria-invalid="true">
                <span class="displayedError fieldEmptyError" id="itemNameEmptyError">
                    <p class="displayedMessage" id="firstNameEmptyMessage">Votre prénom ne peut être laissé vide !</p>
                </span>
            </div>
            <label for="registerLastName">Nom</label>
            <div class="errorDisplayedField">
                <input type="text" name="lastName" id="registerLastName" placeholder="Votre nom" class="notEmptyField" aria-invalid="true">
                <span class="displayedError fieldEmptyError" id="itemNameEmptyError">
                    <p class="displayedMessage" id="lastNameEmptyMessage">Votre nom ne peut être laissé vide !</p>
                </span>
            </div>
        </div>
        <div class="registerPart">
            <label for="registerUsername">Nom d'utilisateur</label>
            <div class="errorDisplayedField">
                <input type="text" name="username" id="registerUsername" placeholder="Votre nom d'utilisateur" class="usernameUniqueField" maxlength="20" aria-invalid="true">
                <span class="displayedError usernameUniqueError" id="registerUsernameUniqueError">
                    <p class="displayedMessage" id="registerUsernameUniqueMessage"></p>
                </span>
            </div>
            <label for="registerEmail">E-mail</label>
            <div class="errorDisplayedField">
                <input type="email" name="email" id="registerEmail" placeholder="Votre email" class="emailField" aria-invalid="true">
                <span class="displayedError emailInvalidError" id="registerEmailInvalidError">
                    <p class="displayedMessage" id="registerEmailInvalidMessage">Vous devez entrer un email valide !</p>
                </span>
            </div>
        </div>
        <div class="registerPart">
            <label for="registerPassword">Mot de passe</label>
            <div class="errorDisplayedField">
                <input type="text" style="display:none;">
                <input type="password" style="display:none;">
                <input type="password" name="password" id="registerPassword" placeholder="Votre mot de passe" class="passwordField" aria-invalid="true">
                <span class="displayedError passwordInvalidError" id="registerPasswordInvalidError">
                    <p class="displayedMessage" id="registerPasswordInvalidMessage">Mot de passe invalide !</p>
                </span>
            </div>
            <label for="registerVerifyPassword">Même mot de passe</label>
            <div class="errorDisplayedField">
                <input type="password" id="registerVerifyPassword" placeholder="Retapez votre mot de passe" class="passwordVerifyField" aria-invalid="true">
                <span class="displayedError passwordVerifyInvalidError" id="registerPasswordVerifyInvalidError">
                    <p class="displayedMessage" id="registerPasswordVerifyInvalidMessage">Mot de passe différent !</p>
                </span>
            </div>
            <label for="registerParticipantCheckbox">Compte de participation uniquement</label>
            <input type="checkbox" name="participant" id="registerParticipantCheckbox">
        </div>
        <div id="registerButtons">
            <button type="button" id="registerPreviousStep">Previous</button>
            <button type="button" id="registerNextStep">Next</button>
        </div>
        <div id="registerSteps">
            <span class="registerStep"></span>
            <span class="registerStep"></span>
            <span class="registerStep"></span>
        </div>
    </form>
</section>
END;
    }

    public function getRequiredCSS()
    {
        return ['/css/form.css', '/css/registration.css'];
    }

    public function getRequiredScripts()
    {
        return ['/js/form.js'];
    }
}