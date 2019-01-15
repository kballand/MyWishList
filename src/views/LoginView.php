<?php

namespace MyWishList\views;


class LoginView implements IView
{
    public function render()
    {
        return
            <<< END
<section class="basicForm">
    <form id="loginForm" method="post" novalidate>
        <label for="loginUsername">Nom d'utilisateur</label>
        <input type="text" id="loginUsername">
        <label for="loginPassword">Mot de passe</label>
        <input type="password" id="loginPassword">
        <p class="errorMessage" id="badLoginMessage">Nom d'utilisateur ou mot de passe incorrect</p>
        <input type="submit" id="loginValidate" class="validateButton" value="Connection">
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
        return ['/js/login.js'];
    }
}