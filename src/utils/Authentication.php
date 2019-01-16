<?php

namespace MyWishList\utils;


use MyWishList\exceptions\AuthException;
use MyWishList\models\AccountModel;

/**
 * Classe permettant de gérer l'authentification
 *
 * @package MyWishList\utils
 */
class Authentication
{
    /**
     * Méthode permettant de s'authentifier
     *
     * @param $username string Nom d'utilisateur
     * @param $password string Mot de passe
     * @return AccountModel Compte de l'utilisateur
     * @throws AuthException
     */
    public static function authenticate($username, $password): AccountModel
    {
        $account = AccountModel::where('username', '=', $username)->first();
        if (isset($account) && password_verify($password, $account->password)) {
            return $account;
        }
        throw new AuthException();
    }

    /**
     * Méthode permettant de charger un profil
     *
     * @param AccountModel $account Compte de l'utilisateur à charger
     */
    public static function loadProfile(AccountModel $account)
    {
        $_SESSION['profile'] = ['username' => $account->username, 'participant' => $account->participant];
    }

    /**
     * Méthode permettant de savoir si l'utilisateur est connecté
     *
     * @return bool Vrai si l'utilisateur est connecté, faux sinon
     */
    public static function hasProfile()
    {
        return isset($_SESSION['profile']);
    }

    /**
     * Méthode permettant de recupérer le profil de l'utilisateur
     *
     * @return mixed Le profil de l'utilsateur
     */
    public static function getProfile()
    {
        return $_SESSION['profile'];
    }

    /**
     * Méthode permettant de supprimer son profil chargé
     */
    public static function deleteProfile()
    {
        unset($_SESSION['profile']);
    }
}