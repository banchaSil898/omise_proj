<?php

use app\models\Folder;
use app\models\Purchase;
use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use richardfan\widget\JSRegister;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;

$categories = Folder::find()->defaultScope()->all();
$bundle = AppAsset::register($this);
?>
<?php
Panel::begin([
    'header' => 'สรุปรายงานคำสั่งซื้อ "ชำระผ่าน บัตรเครดิต และ ธนาคาร"',
    'icon' => 'cubes',
]);
?>
<?php
Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?php
$form = ActiveForm::begin([
            'id' => 'search-frm',
            'action' => ['index'],
            'type' => 'inline',
            'method' => 'get',
            'enableClientValidation' => false,
        ]);
?>
<?=
$form->field($model, 'search[text]', [
    'autoPlaceholder' => false,
    'addon' => [
        'prepend' => [
            'content' => Html::icon('search'),
        ]
    ],
    'inputOptions' => [
        'placeholder' => 'ค้นหา...'
    ],
])->textInput();
?> 
<?=
$form->field($model, 'search[date_start]', [
    'autoPlaceholder' => false,
    'inputOptions' => [
        'placeholder' => 'ตั้งแต่วันที่',
    ],
])->widget(DatePicker::className(), [
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
    ],
    'options' => [
        'placeholder' => 'ตั้งแต่วันที่',
    ],
]);
?> 
<?=
$form->field($model, 'search[date_end]', [
    'autoPlaceholder' => false,
    'inputOptions' => [
        'placeholder' => 'ถึงวันที่',
    ],
])->widget(DatePicker::className(), [
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
    ],
    'options' => [
        'placeholder' => 'ถึงวันที่',
    ],
]);
?> 
<div class="input-group">
    <div class="input-group-addon">การจัดเรียง</div>
    <?=
    Html::dropDownList('sort', Yii::$app->request->get('sort'), [
        '-purchase_no' => 'เลขที่ใบสั่งซื้อ',
        '-transfer_datetime' => 'วันที่แจ้งโอนเงิน',
        '-status' => 'สถานะคำสั่งซื้อ',
            ], [
        'class' => 'form-control',
    ])
    ?>
</div>
<div>
    <?=
    $form->field($model, 'status')->dropDownList(Purchase::getStatusOptionsForSearch(), [
        'prompt' => '(ทุกสถานะ)',
    ]);
    ?> 
    <?=
    $form->field($model, 'payment_method')->dropDownList(Purchase::getPaymentMethodOptions(), [
        'prompt' => '(ทุกช่องทางการชำระเงิน)',
    ]);
    ?> 
    <?=
    $form->field($model, 'delivery_method')->dropDownList(Purchase::getDeliveryOptions(), [
        'prompt' => '(ทุกประเภทการจัดส่ง)',
    ]);
    ?> 
    <?=
    $form->field($model, 'search[ids]')->hiddenInput([
        'id' => 'item',
    ]);
    ?>
    <?= Html::submitButton(Html::icon('search') . ' ค้นหา', ['class' => 'btn btn-primary btn-flat', 'name' => 'mode', 'value' => 'search']); ?>
    <?= Html::submitButton(Html::icon('export') . ' ส่งออกไฟล์ XLS', ['class' => 'btn btn-success btn-flat', 'name' => 'mode', 'value' => 'xls']); ?>
</div>
<?php ActiveForm::end(); ?>
<?=
GridView::widget([
    'id' => 'data-grid',
    'responsiveWrap' => false,
    'dataProvider' => $dataProvider,
    'rowOptions' => function($model) {
        $ret = [];
        if ($model->status == Purchase::STATUS_CANCELLED) {
            $ret['class'] = 'text-strike';
        }
        return $ret;
    },
    'columns' => [
        [
            'class' => SerialColumn::className(),
        ],
        [
            'class' => CheckboxColumn::className(),
        ],
        [
            'attribute' => 'purchase_no',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
            'value' => function($model) {
                return Html::a(Html::encode($model->purchase_no), ['purchase/update', 'id' => $model->id], ['data-pjax' => '0']);
            },
            'format' => 'raw',
        ],
        [
            'label' => 'ที่อยู่ออกใบเสร็จ',
            'value' => function($model) {
                return Html::tag('div', Html::encode($model->invoice_firstname . ' ' . $model->invoice_lastname), ['class' => 'text-bold']) .
                        ($model->invoice_idcard ? Html::tag('div', Html::encode('(' . $model->invoice_idcard . ')')) : '') .
                        ($model->invoice_company ? Html::tag('div', Html::encode($model->invoice_company) . ($model->invoice_tax ? Html::encode(' (' . $model->invoice_tax) . ')' : '')) : '') .
                        Html::tag('div', Html::encode($model->invoice_address . ' ' . $model->invoice_tambon . ' ' . $model->invoice_amphur)) .
                        Html::tag('div', Html::encode($model->invoice_province . ' ' . $model->invoice_postcode . Html::encode(ArrayHelper::getValue($model, 'invoiceCountry.locName', $model->invoice_country)))) .
                        Html::tag('div', Html::encode($model->invoice_phone)) .
                        Html::tag('div', Html::encode($model->invoice_comment))
                ;
            },
            'format' => 'raw',
        ],
        [
            'label' => 'ที่อยู่ส่งสินค้า',
            'value' => function($model) {
                return Html::tag('div', Html::encode($model->delivery_firstname . ' ' . $model->delivery_lastname), ['class' => 'text-bold']) .
                        ($model->delivery_company ? Html::tag('div', Html::encode($model->delivery_company)) : '') .
                        Html::tag('div', Html::encode($model->delivery_address . ' ' . $model->delivery_tambon . ' ' . $model->delivery_amphur)) .
                        Html::tag('div', Html::encode($model->delivery_province . ' ' . $model->delivery_postcode . Html::encode(ArrayHelper::getValue($model, 'invoiceCountry.locName', $model->invoice_country)))) .
                        Html::tag('div', Html::encode($model->delivery_phone)) .
                        Html::tag('div', Html::encode($model->delivery_comment))
                ;
            },
            'format' => 'raw',
        ],
        [
            'label' => 'รายการ',
            'value' => function($model) {
                $html = [];
                foreach ($model->purchaseProducts as $product) {
                    $html[] = Html::tag('div', Html::encode($product->product->name));
                }
                return implode("\n", $html);
            },
            'format' => 'raw',
        ],
        [
            'label' => 'ราคาปก',
            'value' => function($model) {
                $html = [];
                foreach ($model->purchaseProducts as $product) {
                    $html[] = Html::tag('div', Html::encode($product->product->price));
                }
                return implode("\n", $html);
            },
            'format' => 'raw',
        ],
        [
            'label' => 'Price',
            'value' => function($model) {
                $html = [];
                foreach ($model->purchaseProducts as $product) {
                    $html[] = Html::tag('div', Html::encode($product->price_total));
                }
                return implode("\n", $html);
            },
            'format' => 'raw',
        ],
        [
            'label' => 'QTY',
            'value' => function($model) {
                $html = [];
                foreach ($model->purchaseProducts as $product) {
                    $html[] = Html::tag('div', Html::encode($product->amount));
                }
                return implode("\n", $html);
            },
            'format' => 'raw',
        ],
        [
            'label' => 'Total',
            'attribute' => 'price_total',
        ],
        [
            'label' => 'Total',
            'attribute' => 'price_grand',
        ],
        [
            'label' => 'ส่วนลด',
            'value' => function($model) {
                return Html::encode($model->order_note);
            },
        ],
        [
            'label' => 'วิธีส่ง',
            'attribute' => 'delivery_method',
            'value' => function($model) {
                return $model->getDeliveryText();
            },
        ],
        [
            'attribute' => 'status',
            'value' => function ($model) {
                return $model->getHtmlStatus();
            },
            'format' => 'html',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center text-bold',
            ],
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{update}',
            'headerOptions' => [
                'width' => 60,
                'class' => 'text-center',
            ],
            'buttons' => [
                'update' => function($url, $model) {
                    return Html::a(Html::awesome('pencil'), ['purchase/update', 'id' => $model->id]);
                },
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
    ],
]);
?>
<?php Pjax::end(); ?>
<?php Panel::end(); ?>
<?php JSRegister::begin(); ?>
<script>
    $(document).on('beforeSubmit', '#search-frm', function () {
        $('#item').val($('#data-grid').yiiGridView('getSelectedRows'));
        return true;
    });
</script>
<?php JSRegister::end(); ?>