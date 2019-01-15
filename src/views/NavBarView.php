<?php

namespace MyWishList\views;

use MyWishList\utils\Authentication;
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
        return array_unique(array_merge(['/js/navbar.js'], $this->contentView->getRequiredScripts()));
    }

    public function render()
    {
        $router = SlimSingleton::getInstance()->getContainer()->get('router');
        $indexPath = $router->pathFor('index');
        $publicListsPath = $router->pathFor('publicLists');
        $creationPath = $router->pathFor('createList');
        if (Authentication::hasProfile()) {
            $accountPath = $router->pathFor('displayAccount');
            $logoutPath = $router->pathFor('logout');
            $signPossibilities =
                <<< END
<a href="$accountPath" id="myAccountButton">Mon compte</a>
<a href="$logoutPath" id="logoutButton">Se déconnecter</a>
END;
            $reservationsPath = $router->pathFor('displayReservations');
            $additionalPossibilities =
                <<< END
<a href="$reservationsPath" class="subMenuTitle">Mes participations</a>
END;
            if (!Authentication::getProfile()['participant']) {
                $listsPath = $router->pathFor('displayLists');
                $additionalPossibilities .=
                    <<< END
<a href="$creationPath" class="subMenuTitle">Créer une liste</a>
<a href="$listsPath" class="subMenuTitle">Mes listes</a>
END;
            }
        } else {
            $loginPath = $router->pathFor('login');
            $registrationPath = $router->pathFor('registration');
            $additionalPossibilities =
                <<< END
<a href="$creationPath" class="subMenuTitle">Créer une liste</a>
END;
            $signPossibilities =
                <<< END
<a href="$loginPath" id="loginButton">Se connecter</a>
<a href="$registrationPath" id="registerButton">S'enregistrer</a>
END;
        }
        return
            <<< END
<header>
    <h1>Mon header</h1>
</header>
<nav>
	<ul id="menus">
		<li class="menu">
			<a class="menuTitle" href="$indexPath">Accueil</a>
		</li>
		<li class="menu">
			<a class="menuTitle" href="$creationPath">Listes</a>
			<div class="subMenu">
				$additionalPossibilities
				<a href="$publicListsPath" class="subMenuTitle">Consulter les listes publiques</a>
			</div>
		</li>
	</ul>
	<div id="signBar">
		$signPossibilities
    </div>
</nav>
<div class="content">
    {$this->contentView->render()}
</div>
END;
    }
}