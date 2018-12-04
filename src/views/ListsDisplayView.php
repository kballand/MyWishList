<?php
/**
 * Created by PhpStorm.
 * User: Killian
 * Date: 04/12/2018
 * Time: 21:48
 */

namespace MyWishList\views;


use MyWishList\models\ListModel;

class ListsDisplayView implements IView {
    private $lists;

    public function __construct($lists) {
        $this->lists = $lists;
    }

    public function render() {
        if($this->lists instanceof ListModel) {
            return
<<< END
<div id="listContent">
    <h2 class="listTitle">{$this->lists->titre}</h2>
    <p class="listId"><strong>ID</strong> : {$this->lists->no}</p>
    <p class="listDescription"><strong>Description</strong>  : {$this->lists->description}</p>
    <p class="listExpiration"><strong>Date d'expiration</strong> : {$this->lists->expiration}</p>
</div>
END;
        } else {
            $sectionContent = "";
            foreach($this->lists as $list) {
                $sectionContent .=
<<<END
    <a class="listArticle" href="/liste/$list->no">
        <h2 class="listTitle">$list->titre</h2>
        <p class="listId"><strong>ID</strong> : $list->no</p>
        <p class="listDescription"><strong>Description</strong>  : $list->description</p>
        <p class="listExpiration"><strong>Date d'expiration</strong> : $list->expiration</p>
    </a>
END;
            }
            return
<<< END
<section id="listsSection">
    $sectionContent
</section>
END;
        }
    }
}