<?php

namespace MyWishList\views;


use MyWishList\models\AccountModel;
use MyWishList\utils\SlimSingleton;

class AccountDisplayView implements IView
{
    private $account;

    public function __construct(AccountModel $account)
    {
        $this->account = $account;
    }

    public function render()
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $modifyAccountPath = $router->pathFor('modifyAccount');
        return
            <<< END
<div id="accountContent">
    <h2 class="accountUsername">Mon compte</h2>
    <p><strong>Nom d'utilisateur</strong> : {$this->account->username}</p>
    <p><strong>Prénom</strong> : {$this->account->first_name}</p>
    <p><strong>Nom</strong> : {$this->account->last_name}</p>
    <p><strong>E-mail</strong> : {$this->account->email}</p>
    <div class="actionButtons">
        <a id="modifyAccountButton"  href="$modifyAccountPath">Modifier mes informations</a>
    </div>
</div>
END;

    }

    public function getRequiredCSS()
    {
        return ['/css/form.css', '/css/style.css'];
    }

    public function getRequiredScripts()
    {
        return [];
    }
}