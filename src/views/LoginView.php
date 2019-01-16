<?php

namespace MyWishList\views;


use MyWishList\utils\SlimSingleton;

class LoginView implements IView
{
    public function render()
    {
        return
            <<< END
<section class="basicForm">
    <form id="loginForm" method="post" novalidate>
        <label for="loginUsername">Nom d'utilisateur</label>
        <input type="text" name="username" id="loginUsername">
        <label for="loginPassword">Mot de passe</label>
        <input type="password" name="password" id="loginPassword">
        <p class="errorMessage" id="badLoginMessage">Nom d'utilisateur ou mot de passe incorrect</p>
        <input type="submit" id="loginValidate" class="validateButton" value="Connection">
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
        return [$basePath . 'js/login.js'];
    }
}