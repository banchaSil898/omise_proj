<?php

use app\components\grid\DeleteButtonColumn;
use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\CheckboxColumn;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;

$bundle = AppAsset::register($this);
?>
<?php

Panel::begin([
    'header' => 'ของที่ระลึก',
    'icon' => 'gift',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มของที่ระลึก',
            'url' => ['create'],
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
    'responsiveWrap' => false,
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => SerialColumn::className(),
        ],
        [
            'class' => CheckboxColumn::className(),
        ],
        [
            'label' => 'รูป',
            'attribute' => 'cover_url',
            'value' => function($model) {
                return Html::a(Html::img($model->thumbUrl ? $model->thumbUrl : Html::placeholder(48, 48), ['width' => 48]), ['update', 'id' => $model->id], ['data-pjax' => '0']);
            },
            'format' => 'raw',
            'headerOptions' => [
                'width' => 64,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'style' => 'padding:2px;',
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'name',
            'value' => function($model) {
                return Html::a(Html::encode($model->name), ['update', 'id' => $model->id], ['data-pjax' => '0']);
            },
            'format' => 'raw',
        ],
        [
            'class' => EditableColumn::className(),
            'refreshGrid' => true,
            'editableOptions' => [
                'formOptions' => [
                    'action' => ['update-stock-inline']
                ],
                'options' => [
                    'name' => 'stock',
                ]
            ],
            'attribute' => 'stock',
            'format' => 'integer',
            'headerOptions' => [
                'width' => 150,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
        ],
        [
            'attribute' => 'stock_est',
            'format' => 'integer',
            'headerOptions' => [
                'width' => 150,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-right',
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
            'class' => DeleteButtonColumn::className(),
        ],
    ],
]);
?>
<?php Pjax::end(); ?>
<?php Panel::end(); ?>

