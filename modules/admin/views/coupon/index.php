<?php

use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;

$bundle = AppAsset::register($this);
?>
<?php

Panel::begin([
    'header' => 'จัดการคูปอง',
    'icon' => 'ticket',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มคูปอง',
            'url' => ['coupon/create'],
        ],
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มชุดคูปอง',
            'url' => ['coupon-group/create'],
            'linkOptions' => [
                'data-modal' => 1,
            ],
        ]
    ],
]);
?>
<?php

Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?php

$form = ActiveForm::begin([
            'type' => 'inline',
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => '',
                'class' => 'text-right',
            ],
        ]);
?>
<?=

$form->field($model, 'search[text]', [
    'addon' => [
        'prepend' => [
            'content' => Html::icon('search'),
        ]
    ],
])->textInput();
?> 
<?= Html::submitButton('ค้นหา', ['class' => 'btn btn-info btn-flat']); ?>
<?php ActiveForm::end(); ?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => SerialColumn::className(),
        ],
        [
            'class' => CheckboxColumn::className(),
        ],
        [
            'attribute' => 'code',
            'value' => function($model) {
                return Html::a(Html::encode($model->code), ['update', 'id' => $model->id], ['data-pjax' => '0']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'coupon_group_id',
        ],
        [
            'attribute' => 'valid_date',
            'format' => 'datetime',
        ],
        [
            'attribute' => 'expire_date',
            'format' => 'datetime',
        ],
        [
            'attribute' => 'usage_max',
            'format' => 'integer',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'usage_current',
            'format' => 'integer',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        ['class' => ActionColumn::className(),
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
            'class' => ActionColumn::className(),
            'template' => '{delete}',
            'headerOptions' => [
                'width' => 60,
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

