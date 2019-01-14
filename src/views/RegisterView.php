<?php

namespace MyWishList\views;


class RegisterView implements IView
{
    public function render()
    {
        return
            <<< END
<section class="basicForm">
    <form id="registerForm" method="post" novalidate>
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
                <input type="text" name="username" id="registerUsername" placeholder="Votre nom d'utilisateur" class="minLengthField" aria-invalid="true">
                <span class="displayedError fieldMinLengthError" id="usernameMinLengthError">
                    <p class="displayedMessage" id="usernameMinLengthMessage">Votre nom d'utilisateur doit contenir au moins 5 caractères !</p>
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
            <label for="password">Enter your password</label>
            <input type="password" name="password" id="registerPassword" placeholder="Password" required>
            <div class="errorContainer">
                <label class="error" id="passwordError" for="password">Your password must contain at least 5 characters</label>
            </div>
            <label for="password">Enter the same password</label>
            <input type="password" id="registerPasswordChecker" placeholder="Password" required>
            <div class="errorContainer">
                <label class="error" id="passwordCopyError" for="password">This password is different from the previous one</label>
            </div>
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