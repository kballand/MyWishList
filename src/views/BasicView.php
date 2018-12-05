<?php
/**
 * Created by PhpStorm.
 * User: balland64u
 * Date: 04/12/2018
 * Time: 12:19
 */

namespace MyWishList\views;


use MyWishList\utils\SlimSingleton;

class BasicView implements IView {
    private $bodyContent;

    public function __construct($bodyContent) {
        $this->bodyContent = $bodyContent;
    }

    public function render() {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        return
<<< END
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>My Wish List</title>
		<link rel="stylesheet" href="/style.css" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="/script.js"></script>
	</head>
	<body>
		<header>
			<h1>Mon header</h1>
		</header>
		<nav>
			<ul id="menus">
				<li class="menu">
					<a class="menuTitle" href="{$router->pathFor('index')}">Accueil</a>
				</li>
				<li class="menu">
					<a class="menuTitle" href="{$router->pathFor('lists')}">Listes</a>
					<div class="subMenu">
						<a href="" class="subMenuTitle">Afficher mes listes</a>
						<a href="" class="subMenuTitle">Afficher mes listes</a>
					</div>
				</li>
				<li class="menu">
					<a class="menuTitle" href="">Contact</a>
					<div class="subMenu">
						<a href="" class="subMenuTitle">Vers l'accueil</a>
						<a href="" class="subMenuTitle">Vers l'accueil</a>
						<a href="" class="subMenuTitle">Vers l'accueil</a>
						<a href="" class="subMenuTitle">Vers l'accueil</a>
						<a href="" class="subMenuTitle">Vers l'accueil</a>
						<a href="" class="subMenuTitle">Vers l'accueil</a>
					</div>

				</li>
			</ul>
		</nav>
		<div class="content">
		    $this->bodyContent
		</div>
		<footer>

		</footer>
	</body>
</html>
END;
    }
}