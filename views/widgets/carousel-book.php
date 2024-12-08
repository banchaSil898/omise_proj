<?php

use codesk\components\Html;
use yii\helpers\ArrayHelper;
?>
<div class="item item-carousel">
    <div class="books">

        <div class="book">      

            <div class="book-cover-new">
                <?php if ($item->imageUrl): ?>
                    <?= Html::a(Html::img($item->imageUrl), $item->seoUrl); ?>
                <?php else: ?>
                    <?= Html::a(Html::img('http://via.placeholder.com/193x261.jpg'), $item->seoUrl); ?>
                <?php endif; ?>
            </div>


            <div class="book-details">
                <h3 class="book-title">
                    <?= Html::a(Html::encode($item->name), $item->seoUrl); ?>
                </h3>
                <?php if ($item->isCartable): ?>
                    <?php if (ArrayHelper::getValue($options, 'showPrice', true)): ?>
                        <p class="text-bold book-price text-lg">฿ <?= Yii::$app->formatter->asDecimal(Html::encode($item->currentPrice), 2); ?></p>
                        <div class="book-price-cover" style="text-decoration: line-through;">
                            <?php if ($item->currentPrice <> $item->price): ?>
                                ฿ <?= Yii::$app->formatter->asDecimal(Html::encode($item->price), 2); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (ArrayHelper::getValue($options, 'showCartButton', true)): ?>
                        <?php if ($item->isOutOfStock): ?>
                            <?= Html::a('สินค้าหมด', 'javascript:void(0);', ['class' => 'btn btn-danger btn-out-of-stock']); ?>
                        <?php else: ?>
                            <?php if ($item->hasAddOns): ?>
                                <?= Html::a(Html::awesome('shopping-cart') . ' หยิบใส่รถเข็น', ['cart/select-addon', 'id' => $item->id], ['class' => 'btn btn-primary', 'data-modal' => 1, 'data-pjax' => 0]); ?>
                            <?php else : ?>
                                <?= Html::a(Html::awesome('shopping-cart') . ' หยิบใส่รถเข็น', ['cart/add', 'id' => $item->id], ['class' => 'btn btn-primary btn-add-cart']); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-bold book-price text-lg">&nbsp;</p>
                    <div class="book-price-cover">&nbsp;</div>
                    <?= Html::a('ดูรายละเอียด', $item->seoUrl, ['class' => 'btn btn-info']); ?>
                <?php endif; ?>
            </div><!-- /.book-details -->
        </div><!-- /.book -->

    </div><!-- /.books -->
</div><!-- /.item -->