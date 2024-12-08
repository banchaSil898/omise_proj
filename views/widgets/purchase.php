<?php

use app\models\Bank;
use app\models\Purchase;
use app\widgets\Page;
use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
?>
<?php
Page::begin([
    'title' => 'ใบสั่งซื้อเลขที่ ' . Html::tag('span', '#' . $model->purchase_no, ['class' => 'text-primary']),
    'subtitle' => 'เราได้รับคำสั่งซื้อของคุณแล้ว กรุณาโอนเงิน ตามรายละเอียดด้านล่าง'
]);
?>
<div class="row">
    <div class="col-sm-6">
        <h3>รายการสินค้า</h3>
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
                            <td class="text-danger"><?= $promotion['discount'] ? '- ' . Yii::$app->formatter->asDecimal($promotion['discount'], 2) : 'ฟรี'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td colspan="4" class="text-right text-primary text-bold">ราคารวม:</td>
                    <td class="text-primary text-bold"><?= Yii::$app->formatter->asDecimal($model->price_total - $model->price_discount, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right text-warning">ค่าจัดส่ง (<span class="text-info"><?= $model->getDeliveryMethod(); ?></span>):</td>
                    <td class="text-right text-warning"><?= $model->delivery_fee ? Yii::$app->formatter->asDecimal($model->delivery_fee, 2) : '-'; ?></td>
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
        <table class="table table-white">
            <tr>
                <td class="text-right nowrap" style="vertical-align: top;">สถานะ :</td>
                <td class="text-center">
                    <?= $model->getHtmlStatus(); ?>
                </td>
            </tr>
            <?php if ($model->status == Purchase::STATUS_DELIVERIED): ?>
                <tr>
                    <td class="text-right nowrap" style="vertical-align: top;">เลขพัสดุ :</td>
                    <td class="text-center font-sm">
                        <?= $model->status_comment; ?>
                        <div class="row">
                            <?php if ($model->delivery_method == Purchase::DELIVERY_ALPHA): ?>
                                <hr/>
                                <?= Html::a(Html::img('@web/images/web/track-ap.png', ['class' => 'img-resp']), 'https://www.alphafast.com/th/track?id=', ['target' => '_blank']); ?>
                            <?php elseif (in_array($model->delivery_method, [Purchase::DELIVERY_EMS, Purchase::DELIVERY_REGISTER])): ?>
                                <hr/>
                                <?= Html::a(Html::img('@web/images/web/track-tp.png', ['class' => 'img-resp']), 'https://track.thailandpost.co.th', ['target' => '_blank']); ?>
                            <?php else: ?>
                                <div class="col-sm-6">
                                    <?= Html::a(Html::img('@web/images/web/track-tp.png', ['class' => 'img-resp']), 'https://track.thailandpost.co.th', ['target' => '_blank']); ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= Html::a(Html::img('@web/images/web/track-ap.png', ['class' => 'img-resp']), 'https://www.alphafast.com/th/track?id=', ['target' => '_blank']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
        <?php if ($model->getIsTransferNotice()): ?>
            <div class="alert alert-info text-center">
                <?= Html::awesome('clock-o'); ?> แจ้งโอนแล้ว กรุณารอการตรวจสอบ
            </div>
        <?php endif; ?>
        <?php if ($model->getIsWaitForTransfer()): ?>
            <div>
                <p>หลังจากชำระเงินเรียบร้อยแล้ว กรุณาแจ้งโอนเงิน</p>
                <?php
                $form = ActiveForm::begin([
                            'type' => 'horizontal',
                            'enableClientValidation' => false,
                            'formConfig' => [
                                'labelSpan' => 3,
                            ],
                            'options' => [
                                'enctype' => 'multipart/form-data'
                            ],
                ]);
                ?>
                <?= $form->field($model, 'transfer_bank_origin')->dropDownList(ArrayHelper::map(Bank::find()->andWhere(['is_enabled' => '1'])->orderBy(['order_no' => SORT_ASC])->all(), 'id', 'shortName')); ?>
                <?= $form->field($model, 'transfer_bank')->textInput([]); ?>
                <?=
                $form->field($model, 'transfer_date')->widget(DatePicker::className(), [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                    ],
                ]);
                ?>
                <?= $form->field($model, 'transfer_time')->textInput(['placeholder' => date('H:i')]); ?>
                <?= $form->field($model, 'transfer_amount')->textInput([]); ?>
                <?= $form->field($model, 'transferFile')->fileInput([]); ?>
                <div>
                    <?= Html::submitButton('แจ้งโอนเงิน', ['class' => 'btn btn-primary pull-right btn-lg']); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="clearfix"></div>
            <hr/>
        <?php endif; ?>
    </div>
    <div class="col-sm-6">
        <div class="clearfix" style="margin-bottom: 30px;">
            <ul class="progressbar">
                <?=
                Html::tag('li', 'สั่งสินค้า', [
                    'class' => 'active',
                ]);
                ?>
                <?=
                Html::tag('li', 'รอชำระเงิน', [
                    'class' => ($model->isWaitForTransfer || $model->isPaid || $model->isDelivery) ? 'active' : '',
                ]);
                ?>
                <?=
                Html::tag('li', 'ชำระเงินเรียบร้อย', [
                    'class' => ($model->isPaid || $model->isDelivery) ? 'active' : '',
                ]);
                ?>
                <?=
                Html::tag('li', 'จัดส่งสินค้า', [
                    'class' => ($model->isDelivery) ? 'active' : '',
                ]);
                ?>
            </ul>
        </div>
        <?php if ($model->getIsWaitForTransfer()): ?>
            <?= $this->render('/widgets/transfer'); ?>
        <?php endif; ?>
    </div>
</div>
<?php Page::end(); ?>