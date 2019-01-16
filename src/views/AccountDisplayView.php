<?php

namespace MyWishList\views;


use MyWishList\models\AccountModel;
use MyWishList\utils\SlimSingleton;

/**
 * Vue affichant les détails du compte de l'utilsateur
 *
 * @package MyWishList\views
 */
class AccountDisplayView implements IView
{
    /**
     * @var AccountModel Le compte de l'utilsateur
     */
    private $account;

    /**
     * Constructeur de la vue
     *
     * @param AccountModel $account Le compte de l'utilsateur
     */
    public function __construct(AccountModel $account)
    {
        $this->account = $account;
    }

    public function render()
    {
        $router = SlimSingleton::getInstance()->getRouter();
        $deleteAccountPath = $router->pathFor('deleteAccount');
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
        <a id="deleteAccountButton"  href="$deleteAccountPath">Supprimer mon compte</a>
        <a id="modifyAccountButton"  href="$modifyAccountPath">Modifier mes informations</a>
    </div>
</div>
END;
    }

    public function getRequiredCSS()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return [$basePath . 'css/form.css', $basePath . 'css/style.css'];
    }

    public function getRequiredScripts()
    {
        return [];
    }
}