<?php

namespace MyWishList\views;


use MyWishList\utils\SlimSingleton;

/**
 * Vue correspondant à l'affichage d'une page de redirection
 * @package MyWishList\views
 */
class RedirectionView implements IView
{
    /**
     * @var string URL de redirection, titre de la page et description de l'erreur
     */
    private $redirectionUrl, $title, $description;

    /**
     * Constructeur de la vue
     *
     * @param $redirectionUrl string URL de redirection
     * @param $title string Titre de la page
     * @param $description string Description de l'erreur
     */
    public function __construct($redirectionUrl, $title, $description)
    {
        $this->redirectionUrl = $redirectionUrl;
        $this->title = $title;
        $this->description = $description;
    }

    public function getRequiredCSS()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return [$basePath . 'css/redirection.css'];
    }

    public function getRequiredScripts()
    {
        return [];
    }

    public function render()
    {
        header("refresh:5;url=$this->redirectionUrl");
        return
            <<< END
<section id="redirectionSection">
    <h2 id="redirectionTitle">$this->title</h2>
    <p id="redirectionDescription">$this->description</p>
    <p id="redirectionSolver">Si vous n'êtes pas correctement redirigé au bout de 5 secondes cliqués sur <a href="$this->redirectionUrl" id="redirectionLink">ce lien</a></p>
</section>
END;
    }
}