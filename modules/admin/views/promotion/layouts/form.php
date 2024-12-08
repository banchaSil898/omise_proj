<?php

use app\models\Promotion;
use codesk\components\Html;
use yii\bootstrap\Nav;
use yiister\gentelella\widgets\Panel;

$showSubmit = isset($showSubmit) ? $showSubmit : true;
?>
<?php
Panel::begin([
    'header' => 'ข้อมูลโปรโมชั่น' . ($model->isNewRecord ? '' : ' : ' . Html::encode($model->name)),
    'icon' => 'star',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('arrow-left') . ' ย้อนกลับ',
            'url' => ['index'],
            'linkOptions' => [
                'class' => 'btn btn-default',
            ],
        ],
        [
            'encode' => false,
            'label' => Html::icon('floppy-save') . ' บันทึกข้อมูล',
            'linkOptions' => [
                'class' => 'btn btn-save btn-primary',
            ],
            'visible' => $showSubmit,
        ]
    ],
]);
?>
<div class="row">
    <div class="col-md-2">
        <?=
        Nav::widget([
            'options' => [
                'class' => 'nav-product'
            ],
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => Html::awesome('file') . ' ข้อมูลทั่วไป',
                    'url' => ['create'],
                    'active' => $this->context->action->id === 'create',
                    'visible' => $model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('file') . ' ข้อมูลทั่วไป',
                    'url' => ['update', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('dollar') . ' ตั้งค่าโปรโมชั่น',
                    'url' => ['update-condition', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-condition',
                    'visible' => !$model->isNewRecord && count($model->getPromotionManager()->attributeInputs()),
                ],
                [
                    'label' => Html::awesome('th') . ' สินค้าร่วมรายการ' . Html::tag('span', Yii::$app->formatter->asInteger($model->getPromotionProducts()->count()), ['class' => 'label label-info pull-right']),
                    'url' => ['update-product', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-product',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('gift') . ' ของแถม' . Html::tag('span', Yii::$app->formatter->asInteger($model->getPromotionItems()->count()), ['class' => 'label label-info pull-right']),
                    'url' => ['update-item', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-item',
                    'visible' => !$model->isNewRecord && in_array($model->promotion_type, [Promotion::TYPE_PRICE_GET_THING, Promotion::TYPE_THING_GET_THING]),
                ],
                [
                    'label' => Html::awesome('gift') . ' ของที่ระลึก' . Html::tag('span', Yii::$app->formatter->asInteger($model->getPromotionGifts()->count()), ['class' => 'label label-info pull-right']),
                    'url' => ['update-gift', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-gift',
                    'visible' => !$model->isNewRecord && in_array($model->promotion_type, [Promotion::TYPE_GIFT, Promotion::TYPE_GIFT_BY_THING]),
                ],
                [
                    'label' => Html::awesome('gift') . ' สินค้าที่ลดราคาหลังซื้อ' . Html::tag('span', Yii::$app->formatter->asInteger($model->getPromotionItems()->count()), ['class' => 'label label-info pull-right']),
                    'url' => ['update-item', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-item',
                    'visible' => !$model->isNewRecord && in_array($model->promotion_type, [
                        Promotion::TYPE_KEY_TO_DISCOUNT,
                        Promotion::TYPE_KEY_TO_DISCOUNT_P,
                    ]),
                ],
                [
                    'label' => Html::awesome('list') . ' ตั้งเงื่อนไขการแถม',
                    'url' => ['update-gift-rule', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-gift-rule',
                    'visible' => !$model->isNewRecord && in_array($model->promotion_type, [Promotion::TYPE_GIFT, Promotion::TYPE_GIFT_BY_THING]),
                ],
                [
                    'label' => Html::awesome('ticket') . ' คูปอง' . Html::tag('span', Yii::$app->formatter->asInteger($model->getPromotionCoupons()->count()), ['class' => 'label label-info pull-right']),
                    'url' => ['update-coupon', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-coupon',
                    'visible' => !$model->isNewRecord && in_array($model->promotion_type, [Promotion::TYPE_COUPON, Promotion::TYPE_COUPON_P]),
                ],
            ],
        ]);
        ?>
    </div>
    <div id="product-frm" class="col-md-10">
        <?= $content; ?>
    </div>
</div>
<?php Panel::end(); ?>
<?php
$this->registerJs(<<<JS
        $('.btn-save').on("click", function(){
            $('#product-frm form').submit();
            return false;
        });
JS
)?>