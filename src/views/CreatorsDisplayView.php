<?php

namespace MyWishList\views;


use MyWishList\utils\SlimSingleton;

/**
 * Vue correspondant à l'affichage des créateurs de listes publiques
 *
 * @package MyWishList\views
 */
class CreatorsDisplayView implements IView
{
    /**
     * @var array Liste des créateurs de listes publiques
     */
    private $creators;

    /**
     * Constructeur de la vue
     *
     * @param $creators array Liste des créateurs de listes publiques
     */
    public function __construct($creators)
    {
        $this->creators = $creators;
    }

    public function render()
    {
        $sectionContent = "";
        foreach ($this->creators as $creator) {
            $sectionContent .=
                <<<END
    <article class="creatorArticle">
        <h2 class="creatorUsername">$creator->username</h2>
    </article>
END;
        }
        return
            <<< END
<section id="creatorsSection">
    $sectionContent
</section>
END;
    }

    function getRequiredCSS()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return [$basePath . 'css/style.css'];
    }

    public
    function getRequiredScripts()
    {
        return [];
    }
}