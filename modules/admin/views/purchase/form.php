<?php

use app\models\Purchase;
use codesk\components\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $model Purchase */
?>
<?php
$this->beginContent('@module/views/purchase/layouts/form.php', [
    'model' => $model,
]);
?>
<div class="row">
    <div class="col-sm-6">
        <h4 class="title">คำสั่งซื้อ # <?= $model->purchase_no; ?></h4>
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'created_at:datetime',
                [
                    'label' => 'สถานะคำสั่งซื้อ',
                    'attribute' => 'htmlStatus',
                    'format' => 'html',
                ],
                [
                    'label' => 'แจ้งการโอน',
                    'attribute' => 'transfer_file',
                    'value' => function($model) {
                        return implode('', [
                            Html::tag('div', '<strong>บัญชี : </strong>' . (isset($model->transferBank) ? Html::encode($model->transferBank->shortName) : '-')),
                            Html::tag('div', 'โอนจาก ธนาคาร : ' . Html::encode($model->transfer_bank) . ' | ' . Yii::$app->formatter->asDecimal($model->transfer_amount, 2) . ' บาท', ['class' => 'text-primary']),
                            Html::tag('div', '<small>' . Yii::$app->formatter->asDate($model->transfer_date) . ' ' . Html::encode($model->transfer_time) . '</small>', ['class' => 'text-muted text-sm']),
                        ]);
                    },
                    'format' => 'raw',
                    'visible' => function($model) {
                        return $model->transfer_bank ? true : false;
                    },
                ],
                [
                    'label' => 'หลักฐานการโอน',
                    'attribute' => 'transfer_file',
                    'value' => function($model) {
                        return Html::a($model->transfer_file, ['view-transfer', 'id' => $model->id], ['data-modal' => 1, 'data-modal-size' => 'lg']);
                    },
                    'format' => 'raw',
                    'visible' => function($model) {
                        return $model->transfer_file ? true : false;
                    },
                ],
                [
                    'attribute' => 'payment_info',
                    'visible' => $model->isPaid,
                ],
                [
                    'attribute' => 'payment_date',
                    'visible' => $model->isPaid,
                    'format' => 'date',
                ],
                [
                    'attribute' => 'payment_method',
                    'value' => function($a) {
                        return $a->paymentMethod;
                    },
                    'visible' => $model->isPaid,
                ],
                [
                    'attribute' => 'order_note',
                    'visible' => $model->order_note,
                ],
            ],
        ]);
        ?>
    </div>
    <div class="col-sm-6">
        <h4 class="title">ข้อมูลบัญชีผู้ใช้</h4>
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'buyerFullname:text:ชื่อลูกค้า',
                'buyer_email',
            ],
        ]);
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <h4 class="title">ที่อยู่สำหรับออกใบเสร็จ</h4>
        <div style="margin:15px;">
            <div class="text-bold"><?= Html::encode($model->invoice_firstname); ?> <?= Html::encode($model->invoice_lastname); ?></div>
            <div><?= Html::encode($model->invoice_company); ?></div>
            <div><?= Html::encode($model->invoice_address); ?></div>
            <div>
                <?php if ($model->invoice_tambon): ?>
                    ตำบล/แขวง <?= Html::encode($model->invoice_tambon); ?>
                <?php endif; ?>
                <?php if ($model->invoice_amphur): ?>
                    อำเภอ/เขต <?= Html::encode($model->invoice_amphur); ?>
                <?php endif; ?>
            </div>
            <div><?= Html::encode($model->invoice_province); ?> <?= Html::encode($model->invoice_postcode); ?> <?= Html::encode(ArrayHelper::getValue($model, 'invoiceCountry.locName', $model->invoice_country)); ?></div>
            <div>โทร : <?= Html::encode($model->invoice_phone); ?></div>
            <hr/>
            <div><?= Html::encode($model->invoice_comment); ?></div>
        </div>
    </div>
    <div class="col-sm-6">
        <h4 class="title">ที่อยู่สำหรับจัดส่งสินค้า</h4>
        <div style="margin:15px;">
            <div class="text-bold"><?= Html::encode($model->delivery_firstname); ?> <?= Html::encode($model->delivery_lastname); ?></div>
            <div><?= Html::encode($model->delivery_company); ?></div>
            <div><?= Html::encode($model->delivery_address); ?></div>
            <div>
                <?php if ($model->delivery_tambon): ?>
                    ตำบล/แขวง <?= Html::encode($model->delivery_tambon); ?>
                <?php endif; ?>
                <?php if ($model->delivery_amphur): ?>
                    อำเภอ/เขต <?= Html::encode($model->delivery_amphur); ?>
                <?php endif; ?>
            </div>
            <div><?= Html::encode($model->delivery_province); ?> <?= Html::encode($model->delivery_postcode); ?> <?= Html::encode(ArrayHelper::getValue($model, 'deliveryCountry.locName', $model->delivery_country)); ?></div>
            <div>โทร : <?= Html::encode($model->delivery_phone); ?></div>
            <hr/>
            <div><?= Html::encode($model->delivery_comment); ?></div>
        </div>
    </div>
</div>
<h4 class="title">รายการสินค้า</h4>
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
        <tr>
            <td colspan="4" class="text-right">ราคารวม:</td>
            <td class="text-right"><?= Yii::$app->formatter->asDecimal($model->price_total, 2); ?></td>
        </tr>
        <?php $promotions = $model->getPromotionSummary(); ?>
        <?php if (isset($promotions['info'])): ?>
            <?php foreach ($promotions['info'] as $promotion): ?>
                <tr>
                    <td colspan="4" class="text-right text-danger">
                        <?= $promotion['name']; ?>
                        <?php if (isset($promotion['detail']) && $promotion['detail']): ?>
                            <?= Html::tag('div', $promotion['detail']); ?>
                        <?php endif; ?>
                    </td>
                    <td class="text-danger text-right"><?= $promotion['discount'] ? '- ' . Yii::$app->formatter->asDecimal($promotion['discount'], 2) : 'ฟรี'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <tr>
            <td colspan="4" class="text-right">ค่าจัดส่ง:</td>
            <td class="text-right"><?= Yii::$app->formatter->asDecimal($model->delivery_fee, 2); ?></td>
        </tr>
        <tr>
            <td colspan="4" class="text-right text-bold">จำนวนเงินที่ต้องชำระ:</td>
            <td class="text-right text-bold"><?= Yii::$app->formatter->asDecimal($model->price_grand, 2); ?></td>
        </tr>
    </tfoot><!-- /tfoot -->
    <tbody class="index-dropdown-body">
        <?php if (!$model->purchaseProducts): ?>
            <tr class="cart-book">
                <td colspan="5" class="text-center">
                    ไม่มีรายการสินค้าที่เลือกไว้
                </td>
            </tr>
        <?php else: ?>
            <?php $i = 1; ?>
            <?php foreach ($model->purchaseProducts as $item): ?>
                <tr class="cart-book">
                    <td class="text-center"><?= ($i); ?>.</td>
                    <td>
                        <?= Html::encode($item->product->name); ?>
                        <?php if (isset($item->addon_id)): ?>
                            <div><small class="text-primary"><?= Html::encode($item->addon_name); ?></small></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <?= Yii::$app->formatter->asDecimal(Html::encode($item->price - ($item->price_addon ? $item->price_addon : 0)), 2); ?>
                        <?php if (isset($item->addon_id)): ?>
                            <div><small class="text-success">+ <?= Html::encode($item->price_addon, 2); ?></small></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-center"><?= Html::encode($item->amount); ?></td>
                    <td class="text-right"><?= Yii::$app->formatter->asDecimal(Html::encode($item->price_total), 2); ?></td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody><!-- /tbody -->
</table>
<?php $this->endContent(); ?>
