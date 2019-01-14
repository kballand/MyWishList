<?php

namespace MyWishList\controllers;


class ShareController
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ShareController();
        }
        return self::$instance;
    }

    public function shareList($no){
        $liste = Liste::where("token", "=", $no)->get();
        $vue = new vue($liste);
        $vue->render(6);
    }

    public function setMsg(){
        $id=$_POST["no"];
        $li=Liste::where("no","=",$id)->first();

        $message=filter_var($_POST['message'], FILTER_SANITIZE_STRING);
        $li->message=$message;

        $li->save();
        $this->getListeList();
    }
}