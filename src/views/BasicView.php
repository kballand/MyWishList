<?php

namespace MyWishList\views;

use MyWishList\utils\CommonUtils;
use MyWishList\utils\SlimSingleton;

/**
 * Classe présentant la vue basique du site qui possède le contenu d'une autre vue
 *
 * @package MyWishList\views
 */
class BasicView implements IView
{
    /**
     * @var IView Vue interne à afficher
     */
    private $bodyView;
    /**
     * @var string Titre de la page
     */
    private $title;

    /**
     * Contructeur de la vue
     * @param IView $bodyView Vue interne à afficher
     * @param string $title Titre de la page
     */
    public function __construct(IView $bodyView, $title = 'MyWishList')
    {
        $this->bodyView = $bodyView;
        if (isset($title) && is_string($title)) {
            $this->title = $title;
        } else {
            $this->title = 'MyWishList';
        }
    }

    public function render()
    {
        $css = CommonUtils::importCSS($this->getRequiredCSS());
        $scripts = CommonUtils::importScripts($this->getRequiredScripts());
        return
            <<< END
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title>$this->title</title>
		$css
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
		$scripts
	</head>
	<body>
	    {$this->bodyView->render()}
	</body>
</html>
END;
    }

    public function getRequiredCSS()
    {
        $basePath = SlimSingleton::getInstance()->getBasePath();
        return array_unique(array_merge([$basePath . 'css/common.css'], $this->bodyView->getRequiredCSS()));
    }

    public function getRequiredScripts()
    {
        return $this->bodyView->getRequiredScripts();
    }
}