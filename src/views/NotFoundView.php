<?php

namespace MyWishList\views;


use MyWishList\utils\SlimSingleton;

class NotFoundView implements IView
{
    private $urlRequested;

    public function __construct($urlRequested)
    {
        $this->urlRequested = $urlRequested;
    }


    public function render()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return
            <<< END
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Error 404 (Not Found)</title>
		<link rel="stylesheet" href="{$basePath}css/style.css" />
	</head>
	<body>
		<div id="notFound">
		    <h1 id="errorTitle"><strong>Error 404</strong> : Not Found !</h1>
		    <p id="errorDesc">The requested URL <strong>$this->urlRequested</strong> was not found on this server.</p>
		</div>
	</body>
</html>
END;
    }

    public function getRequiredCSS()
    {
        return [];
    }

    public function getRequiredScripts()
    {
        return [];
    }
}