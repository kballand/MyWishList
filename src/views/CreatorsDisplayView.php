<?php

namespace MyWishList\views;


use MyWishList\utils\SlimSingleton;

class CreatorsDisplayView implements IView
{
    private $creators;

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