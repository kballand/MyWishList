<?php

namespace MyWishList\utils;


use MyWishList\models\AccountModel;

class Authentication
{
    public static function authenticate($username, $password): AccountModel
    {

    }

    public static function loadProfile(AccountModel $account)
    {
        $_SESSION['profile'] = ['username' => $account->username, 'participant' => $account->participant];
    }

    public static function hasProfile()
    {
        return isset($_SESSION['profile']);
    }

    public static function getProfile()
    {
        return $_SESSION['profile'];
    }

    public static function deleteProfile()
    {
        unset($_SESSION['profile']);
    }
}