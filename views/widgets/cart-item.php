<?php

use app\models\Product;
use codesk\components\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$showCover = isset($showCover) ? $showCover : true;
/* @var $item['model'] Product */
?>
<tr class="cart-book">

    <td>
        <div class="media">
            <?php if ($showCover): ?>
                <?= Html::a(Html::tag('div', Html::img($item['model']->thumbUrl, ['class' => 'media-object', 'height' => 90]) . Html::tag('div', '', ['class' => 'fade']), ['class' => 'book-cover small-book-cover book-cover-xs']), $item['model']->seoUrl, ['class' => 'cart-thumbnail-image media-left', 'data-pjax' => '0']); ?>
            <?php endif; ?>
            <div class="media-body">
                <h3 class="media-heading">
                    <?= Html::a(Html::encode($item['model']->name), $item['model']->seoUrl, ['data-pjax' => '0']); ?>
                </h3>
                <?php if (isset($item['addon'])): ?>
                    <?= Html::tag('div', Html::encode($item['addon']->name), ['class' => 'font-xs', 'style' => 'padding-left:30px;']); ?>
                <?php endif; ?>
            </div><!-- /.media-body -->
        </div><!-- /.media -->
    </td>

    <td>
        <span class="price">฿ <?= Html::encode($item['model']->currentPrice); ?></span>
        <?php if (isset($item['addon'])): ?>
            <?= Html::tag('div', '+฿ ' . Yii::$app->formatter->asDecimal($item['addon']->price, 2), ['class' => 'font-xs text-success']); ?>
        <?php endif; ?>
    </td>
    <td class="quantity dark text-center">            
        <div class="quant-input">
            <div class="arrows">
                <div class="arrow plus gradient">
                    <?= Html::a(Html::icon('plus'), ['cart/add', 'id' => $item['model']->id], ['class' => 'btn-cart-update']); ?>
                </div>
                <div class="arrow minus gradient">
                    <?= Html::a(Html::icon('minus'), ['cart/remove', 'id' => $item['model']->id], ['class' => 'btn-cart-update']); ?>
                </div>
            </div>
            <input class="cart-qty-box txt-quantity" type="text" data-url="<?= Url::to(['cart/set-qty', 'id' => $item['model']->id]); ?>" value="<?= Html::encode($item['amount']); ?>"/>
        </div>              
    </td>
    <td>
        <span class="price subtotal">฿ <?= Html::encode(ArrayHelper::getValue($item, 'total', 0)); ?></span>
    </td>
    <td>
        <?= Html::a('X', ['cart/clear', 'id' => $item['model']->id], ['class' => 'btn btn-primary btn-flat btn-cart-update']); ?>
    </td>
</tr><!-- /.cart-book -->