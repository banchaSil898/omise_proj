<?php

use codesk\components\Html;
?>                
<div class="item item-news-block news">
    <div class="book-cover-new">
        <?= Html::a(Html::tag('div', Html::img('@web/images/blank.gif', ['data-echo' => $item->backgroundUrl]), ['class' => 'text-center', 'style' => 'height:120px;']), ['content/view', 'id' => $item->id]); ?>
    </div>
    <div class="news-details text-center">
        <h3 class="news-name">
            <?= Html::a(Html::encode($item->name), ['content/view', 'id' => $item->id]); ?>
        </h3>
    </div><!-- /.author-details -->
</div><!-- /.item -->