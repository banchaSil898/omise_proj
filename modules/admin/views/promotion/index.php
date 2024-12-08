<?php

use app\components\grid\DeleteButtonColumn;
use app\components\grid\OrderButtonColumn;
use app\models\Promotion;
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
    'header' => 'รายการโปรโมชั่น',
    'icon' => 'star',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มโปรโมชั่น',
            'url' => ['create'],
            'linkOptions' => [
                'class' => 'btn btn-primary',
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
            'class' => OrderButtonColumn::className(),
        ],
        [
            'attribute' => 'name',
            'value' => function($model) {
                return Html::a(Html::encode($model->name), ['update', 'id' => $model->id], ['data-pjax' => '0']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'promotion_type',
            'value' => function($model) {
                return Html::encode(Promotion::getTypeOptions($model->promotion_type));
            }
        ],
        [
            'attribute' => 'date_start',
            'format' => 'datetime',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
        ],
        [
            'attribute' => 'date_end',
            'format' => 'datetime',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
        ],
        [
            'attribute' => 'is_active',
            'headerOptions' => [
                'width' => 80,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center kv-align-middle',
            ],
            'value' => function($model) {
                return $model->is_active ? Html::a(Html::icon('ok'), ['toggle-status', 'id' => $model->id, 'attribute' => 'is_active'], ['class' => 'text-success', 'data-ajax' => '1', 'data-ajax-method' => 'post', 'data-ajax-pjax-reload' => '#pjax-page']) : Html::a(Html::icon('remove'), ['toggle-status', 'id' => $model->id, 'attribute' => 'is_active'], ['class' => 'text-danger', 'data-ajax' => '1', 'data-ajax-method' => 'post', 'data-ajax-pjax-reload' => '#pjax-page']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'status',
            'format' => 'raw',
            'headerOptions' => [
                'width' => 120,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center kv-align-middle',
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

