<?php

use codesk\components\Html;
?>                
<div class="item item-author-block author">
    <?= Html::a(Html::tag('div', Html::img('@web/images/blank.gif', ['data-echo' => Yii::getAlias($item['imageUrl'])]), ['class' => 'text-center author-dp']), ['author/view', 'id' => $item->id]); ?>
    <div class="author-details text-center">
        <h3 class="author-name">
            <?= Html::a(Html::encode($item->name), ['author/view', 'id' => $item->id]); ?>
        </h3>
        <?= Html::tag('blockquote', $item->description, ['class' => 'author-testimonial']); ?>
        <?= Html::a('รายชื่อหนังสือ', ['author/view', 'id' => $item->id], ['class' => 'btn btn-primary btn-view-books']); ?>
    </div><!-- /.author-details -->
</div><!-- /.item -->