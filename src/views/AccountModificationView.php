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

    }

    public function getRequiredCSS()
    {
        return ['/css/registration.css', '/css/form.css'];
    }

    public function getRequiredScripts()
    {
        return ['/js/form.js'];
    }
}