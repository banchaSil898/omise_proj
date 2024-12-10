<?php

use codesk\components\Html;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
?>
<table class="table table-cart table-cart-checkout index-dropdown-table">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="dark text-center">สินค้า</th>
            <th class="text-right">ราคา(฿)</th>
            <th class="dark text-center">จำนวน</th>
            <th class="text-right">รวม(฿)</th>
        </tr>
    </thead><!-- /thead-->
    <tfoot>
        <?php if (isset($promotions['info'])): ?>
            <?php foreach ($promotions['info'] as $promotion): ?>
                <?php if ($promotion['showOnCart']): ?>
                    <tr>
                        <td colspan="4" class="text-right text-danger">
                            <?= $promotion['name']; ?>
                            <?php if (isset($promotion['detail']) && $promotion['detail']): ?>
                                <?= Html::tag('div', $promotion['detail']); ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-danger"><span style="white-space: nowrap"><?= $promotion['discount'] > 0 ? '- ' . Yii::$app->formatter->asDecimal($promotion['discount'], 2) : 'ฟรี'; ?></span></td>
                    </tr>
                    <?php if (isset($cart->products[$promotion['id']])): ?>
                        <?php foreach ($cart->products[$promotion['id']] as $item): ?>
                            <tr class="cart-book">
                                <td class="text-center"><?= Html::a('[ลบ]', ['promotion/product-clear', 'product_id' => $item['model']->id, 'promotion_id' => $promotion['id']], ['class' => 'btn-remove-promotion']); ?></td>
                                <td><?= Html::encode($item['model']->name); ?></td>
                                <td class="text-right"></td>
                                <td class="text-center"><?= Html::encode($item['amount']); ?></td>
                                <td class="text-right"></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <tr>
            <td colspan="4" class="text-right text-primary text-bold">ราคารวม:</td>
            <td class="text-right text-primary text-bold"><?= Yii::$app->formatter->asDecimal($cart->totalAfterDiscount, 2); ?></td>
        </tr>
        <?php if ($cart->delivery_method): ?>
            <tr>
                <td colspan="4" class="text-right text-warning">ค่าจัดส่งสินค้า (<?= Yii::$app->formatter->asInteger($cart->weight); ?> กรัม):</td>
                <td class="text-right text-warning"><span style="white-space: nowrap"><?= $cart->deliveryTotal > 0 ? Yii::$app->formatter->asDecimal($cart->deliveryTotal, 2) : 'ฟรี'; ?></span></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-right text-warning">กรุณาเลือกช่องทางในการจัดส่ง</td>
                <td>-</td>
            </tr>
        <?php endif; ?>
        <tr>
            <td colspan="4" class="text-right text-bold">จำนวนเงินที่ต้องชำร:</td>
            <td class="text-right text-bold" id="grandtotal"><?= Yii::$app->formatter->asDecimal($cart->grandTotal, 2); ?></td>
        </tr>
    </tfoot><!-- /tfoot -->
    <tbody class="index-dropdown-body">
        <?php if (!$cart->items): ?>
            <tr class="cart-book">
                <td colspan="5" class="text-center">
                    ไม่มีรายการสินค้าที่เลือกไว้
                </td>
            </tr>
        <?php else: ?>
            <?php $i = 1; ?>
            <?php foreach ($cart->items as $item): ?>
                <tr class="cart-book">
                    <td class="text-center"><?= ($i); ?>.</td>
                    <td>
                        <?= Html::encode($item['model']->name); ?>
                        <?php if (isset($item['addon'])): ?>
                            <small class="text-primary"><?= Html::encode($item['addon']->name); ?></small>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <?= Yii::$app->formatter->asDecimal(Html::encode($item['model']->currentPrice), 2); ?>
                        <?php if (isset($item['addon'])): ?>
                            <div>
                                <small class="text-success">+<?= Yii::$app->formatter->asDecimal($item['addon']->price, 2); ?></small>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="text-center"><?= Html::encode($item['amount']); ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal(Html::encode(ArrayHelper::getValue($item, 'total', 0)), 2); ?></td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody><!-- /tbody -->
</table>
<?php if (isset($promotions['gifts']) && is_array($promotions['gifts']) && count($promotions['gifts'])): ?>
    <h4 class="text-bold">กรุณาเลือกของที่ระลึก</h4>
    <table class="table table-condensed table-bordered">
        <?php foreach ($promotions['gifts'] as $gift): ?>
            <?php
            $mark = Yii::$app->session->get('gift');
            ?>
            <tr class="cart-book">
                <td class="text-center">
                    <?=
                    Html::radio('gift', isset($mark) ? $mark['id'] == $gift->id : false, [
                        'value' => $gift->name,
                        'data-id' => $gift->id,
                        'data-attribute' => 'name',
                        'class' => 'dd-gift-change'
                    ]);
                    ?>
                </td>
                <td class="text-center" style="padding:0;width:64px;">
                    <?= Html::a(Html::img($gift->thumbUrl, ['style' => 'width:100%;', 'class' => '']), ['gift/example', 'id' => $gift->id], ['data-modal' => 1]); ?>
                </td>
                <td><?= Html::encode($gift->name); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>