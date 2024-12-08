<?php

use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;

$bundle = AppAsset::register($this);
?>
<?php

Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?php

Panel::begin([
    'header' => 'บัญชีผู้ใช้งาน',
    'icon' => 'user',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มผู้ใช้',
            'url' => ['create'],
            'linkOptions' => [
                'data-pjax' => '0',
            ],
        ]
    ],
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
            'attribute' => 'username',
            'value' => function($model) {
                return Html::a(Html::encode($model->username), ['update', 'id' => $model->id], ['data-pjax' => '0']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'role.name',
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 180,
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'header' => 'แก้ไข',
            'class' => ActionColumn::className(),
            'template' => '{update}',
            'buttons' => [
                'update' => function($url) {
                    return Html::a(Html::icon('pencil'), $url, ['data-pjax' => '0']);
                },
            ],
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 80,
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
            'visibleButtons' => [
                'delete' => function($model) {
                    return Yii::$app->user->id <> $model->id;
                }
            ],
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 80,
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
    ],
]);
?>
<?php Panel::end(); ?>
<?php Pjax::end(); ?>