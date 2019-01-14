<?php

namespace MyWishList\views;

use MyWishList\utils\SlimSingleton;

class NavBarView implements IView
{
    private $contentView;

    public function __construct(IView $contentView)
    {
        $this->contentView = $contentView;
    }

    public function getRequiredCSS()
    {
        return array_unique(array_merge(['/css/navbar.css', '/css/style.css'], $this->contentView->getRequiredCSS()));
    }

    public function getRequiredScripts()
    {
        return array_unique(array_merge(['/js/script.js'], $this->contentView->getRequiredScripts()));
    }

    public function render()
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $index = $router->pathFor('index');
        $lists = $router->pathFor('displayLists');
        $register = $router->pathFor('registration');
        $creation = $router->pathFor('createList');
        return
            <<< END
<header>
    <h1>Mon header</h1>
</header>
<nav>
	<ul id="menus">
		<li class="menu">
			<a class="menuTitle" href="$index">Accueil</a>
		</li>
		<li class="menu">
			<a class="menuTitle" href="$lists">Listes</a>
			<div class="subMenu">
				<a href="$lists" class="subMenuTitle">Mes listes</a>
				<a href="$creation" class="subMenuTitle">Créer une liste</a>
			</div>
		</li>
	</ul>
	<span id="signBar">
		<form id="loginForm">
		    <input type="text" name="username" id="loginUsername" placeholder="Username">
		    <input type="password" name="password" id="loginPassword" placeholder="Password">
		    <input type="submit" id="loginSubmit" value="Se connecter">
		    <input type="button" id="registerButton" value="S'enregistrer" onclick="window.location.href='$register'">
        </form>
    </span>
</nav>
<div class="content">
    {$this->contentView->render()}
</div>
<footer>

</footer>
END;
    }
}