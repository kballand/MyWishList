<?php

namespace MyWishList\views;

use MyWishList\utils\CommonUtils;

class BasicView implements IView
{
    private $bodyView;
    private $title;

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
        return array_unique(array_merge(['/css/common.css'], $this->bodyView->getRequiredCSS()));
    }

    public function getRequiredScripts()
    {
        return $this->bodyView->getRequiredScripts();
    }
}