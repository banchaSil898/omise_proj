<?php

use app\models\Folder;
use app\models\Purchase;
use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;

$categories = Folder::find()->defaultScope()->all();
$bundle = AppAsset::register($this);
?>
<?php
Panel::begin([
    'header' => 'รายการสั่งซื้อ',
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
            'action' => ['index'],
            'type' => 'inline',
            'method' => 'get',
            'enableClientValidation' => false,
        ]);
?>
<div class="well well-sm">
    <div> 
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
        ])->widget(DatePicker::className(), [
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ],
            'options' => [
                'placeholder' => 'ตั้งแต่วันที่...'
            ],
        ]);
        ?> 
        <?=
        $form->field($model, 'search[date_end]', [
            'autoPlaceholder' => false,
        ])->widget(DatePicker::className(), [
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ],
            'options' => [
                'placeholder' => 'ถึงวันที่...'
            ],
        ]);
        ?> 
    </div>
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
    <?= Html::submitButton('ค้นหา', ['class' => 'btn btn-info btn-flat', 'name' => 'model', 'value' => 'search']); ?>
    <?= Html::submitButton(Html::icon('export') . ' ส่งออกไฟล์ XLS', ['class' => 'btn btn-success btn-flat', 'name' => 'mode', 'value' => 'xls']); ?>
</div>
<?php ActiveForm::end(); ?>
<?=
GridView::widget([
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
            'attribute' => 'purchase_no',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
            'value' => function($model) {
                return Html::a(Html::encode($model->purchase_no), ['update', 'id' => $model->id], ['data-pjax' => '0']);
            },
            'format' => 'html',
        ],
        [
            'label' => 'ชื่อผู้สั่งซื้อ',
            'attribute' => 'buyerFullnameWithId',
            'format' => 'html',
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
            'attribute' => 'status_comment',
            'value' => function($model) {
                return implode('<br/>', [$model->status_comment, $model->order_note]);
            },
            'format' => 'html',
            'headerOptions' => [
                'style' => 'width:250px;',
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'login_type',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'buyer_email',
        ],
        [
            'label' => 'การจัดส่ง',
            'attribute' => 'deliveryText',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'price_grand',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
        ],
        [
            'attribute' => 'created_at',
            'format' => 'datetime',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{update}',
            'headerOptions' => [
                'width' => 60,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'header' => 'ลบ',
            'class' => ActionColumn::className(),
            'template' => '{delete}',
            'buttons' => [
                'delete' => function($url) {
                    return Html::a(Html::icon('trash'), $url, [
                                'data-pjax' => '0',
                                'data-ajax' => '1',
                                'data-ajax-method' => 'post',
                                'data-ajax-pjax-reload' => '#pjax-page',
                                'data-ajax-confirm' => 'ต้องการลบรายการนี้?',
                    ]);
                },
            ],
            'headerOptions' => [
                'class' => 'text-center',
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