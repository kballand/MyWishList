<?php

namespace MyWishList\utils;


use MyWishList\exceptions\AuthException;
use MyWishList\models\AccountModel;

class Authentication
{
    public static function authenticate($username, $password): AccountModel
    {
        $account = AccountModel::where('username', '=', $username)->first();
        if (isset($account) && password_verify($password, $account->password)) {
            return $account;
        }
        throw new AuthException();
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