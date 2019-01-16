<?php

namespace MyWishList\views;

use MyWishList\utils\Authentication;
use MyWishList\utils\SlimSingleton;

/**
 * Vue correspondant à la barre de navigation
 * @package MyWishList\views
 */
class NavBarView implements IView
{
    /**
     * @var IView Vue de contenu du site
     */
    private $contentView;

    /**
     * Constructeur de la vue
     * @param IView $contentView Vue de contenu du site
     */
    public function __construct(IView $contentView)
    {
        $this->contentView = $contentView;
    }

    public function getRequiredCSS()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return array_unique(array_merge([$basePath . 'css/navbar.css', $basePath . 'css/style.css'], $this->contentView->getRequiredCSS()));
    }

    public function getRequiredScripts()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return array_unique(array_merge([$basePath . 'js/navbar.js'], $this->contentView->getRequiredScripts()));
    }

    public function render()
    {
        $router = SlimSingleton::getInstance()->getRouter();
        $indexPath = $router->pathFor('index');
        $publicListsPath = $router->pathFor('publicLists');
        $creatorsPath = $router->pathFor('creators');
        $creationPath = $router->pathFor('createList');
        if (Authentication::hasProfile()) {
            $accountPath = $router->pathFor('displayAccount');
            $logoutPath = $router->pathFor('logout');
            $signPossibilities =
                <<< END
<a href="$accountPath" id="myAccountButton" class="textTitle">Mon compte</a>
<a href="$accountPath" class="iconTitle"><i class="fas fa-user"></i></a>
<a href="$logoutPath" id="logoutButton" class="textTitle">Se déconnecter</a>
<a href="$logoutPath" class="iconTitle"><i class="fas fa-sign-out-alt"></i></a>
END;
            $reservationsPath = $router->pathFor('displayReservations');
            $additionalPossibilities =
                <<< END
<a href="$reservationsPath" class="subMenuTitle">Mes participations</a>
END;
            if (!Authentication::getProfile()['participant']) {
                $listsPath = $router->pathFor('displayLists');
                $associateListPath = $router->pathFor('associateList');
                $additionalPossibilities .=
                    <<< END
<a href="$creationPath" class="subMenuTitle">Créer une liste</a>
<a href="$listsPath" class="subMenuTitle">Mes listes</a>
<a href="$associateListPath" class="subMenuTitle">Associer une liste</a>
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
<a href="$loginPath" id="loginButton" class="textTitle">Se connecter</a>
<a href="$loginPath" id="loginButton" class="iconTitle"><i class="fas fa-sign-in-alt"></i></a>
<a href="$registrationPath" id="registerButton" class="textTitle">S'enregistrer</a>
<a href="$registrationPath" id="registerButton" class="iconTitle"><i class="fas fa-user-plus"></i></a>
END;
        }
        return
            <<< END

<nav>
	<ul id="menus">
		<li class="menu">
			<a class="menuTitle textTitle" href="$indexPath">Accueil</a>
			<a class="menuTitle iconTitle" href="$indexPath"><i class="fas fa-home"></i></a>
		</li>
		<li class="menu">
			<a class="menuTitle textTitle" href="$creationPath">Listes</a>
			<a class="menuTitle iconTitle" href="$creationPath"><i class="fas fa-bars"></i></a>
			<div class="subMenu">
				$additionalPossibilities
				<a href="$publicListsPath" class="subMenuTitle">Consulter les listes publiques</a>
				<a href="$creatorsPath" class="subMenuTitle">Afficher la liste des créateurs</a>
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