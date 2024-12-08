<?php

use codesk\components\Html;
?>
<div class="book">      

    <div class="book-cover-new">
        <?php if ($model->imageUrl): ?>
            <?= Html::a(Html::img($model->imageUrl), $model->seoUrl); ?>
        <?php else: ?>
            <?= Html::a(Html::img('http://via.placeholder.com/193x261.jpg'), $model->seoUrl); ?>
        <?php endif; ?>
    </div><!-- /.book-inner -->

    <div class="book-details">
        <h3 class="book-title">
            <?= Html::a(Html::encode($model->name), $model->seoUrl); ?>
        </h3>
        <?php if ($model->isCartable): ?>
            <p class="book-price">฿ <?= Yii::$app->formatter->asDecimal(Html::encode($model->currentPrice), 2); ?></p>
            <?php if ($model->currentPrice <> $model->price): ?>
                <div class="book-price-cover" style="text-decoration: line-through;">฿ <?= Yii::$app->formatter->asDecimal(Html::encode($model->price), 2); ?></div>
            <?php else: ?>
                <div class="book-price-cover" style="text-decoration: line-through;"></div>
            <?php endif; ?>
            <?php if ($model->isOutOfStock): ?>
                <?= Html::a('สินค้าหมด', '#', ['class' => 'btn btn-danger btn-out-of-stock']); ?>
            <?php else: ?>
                <?php if ($model->hasAddOns): ?>
                    <?= Html::a(Html::awesome('shopping-cart') . ' หยิบใส่รถเข็น', ['cart/select-addon', 'id' => $model->id], ['class' => 'btn btn-primary', 'data-modal' => 1, 'data-pjax' => 0]); ?>
                <?php else : ?>
                    <?= Html::a(Html::awesome('shopping-cart') . ' หยิบใส่รถเข็น', ['cart/add', 'id' => $model->id], ['class' => 'btn btn-primary btn-add-cart']); ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <p class="book-price">&nbsp;</p>
            <div class="book-price-cover">&nbsp;</div>
            <?= Html::a('ดูรายละเอียด', $model->seoUrl, ['class' => 'btn btn-info']); ?>
        <?php endif; ?>
    </div><!-- /.book-details -->
</div><!-- /.book -->