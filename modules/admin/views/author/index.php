<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;
?>
<?php

Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?php

Panel::begin([
    'header' => 'จัดการข้อมูลผู้แต่ง',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มผู้แต่ง',
            'url' => ['create'],
            'linkOptions' => [
                'data-modal' => '1',
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
        ],
    ],
])->textInput([
    'placeholder' => 'ค้นหา...',
]);
?>
<?php ActiveForm::end(); ?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => SerialColumn::className(),
        ],
        [
            'attribute' => 'photo_url',
            'value' => function($model) {
                return Html::a(Html::img($model->thumbUrl, ['width' => 48]), ['update', 'id' => $model->id], ['data-modal' => '1', 'data-pjax' => '0']);
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
        ],
        [
            'attribute' => 'is_recommended',
            'headerOptions' => [
                'width' => 60,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center kv-align-middle',
            ],
            'value' => function($model) {
                return $model->is_recommended ? Html::a(Html::icon('ok'), ['toggle-status', 'id' => $model->id, 'attribute' => 'is_recommended'], ['class' => 'text-success', 'data-ajax' => '1', 'data-ajax-method' => 'post', 'data-ajax-pjax-reload' => '#pjax-page']) : Html::a(Html::icon('remove'), ['toggle-status', 'id' => $model->id, 'attribute' => 'is_recommended'], ['class' => 'text-danger', 'data-ajax' => '1', 'data-ajax-method' => 'post', 'data-ajax-pjax-reload' => '#pjax-page']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'is_hide',
            'headerOptions' => [
                'width' => 60,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center kv-align-middle',
            ],
            'value' => function($model) {
                return !$model->is_hide ? Html::a(Html::icon('ok'), ['toggle-status', 'id' => $model->id, 'attribute' => 'is_hide'], ['class' => 'text-success', 'data-ajax' => '1', 'data-ajax-method' => 'post', 'data-ajax-pjax-reload' => '#pjax-page']) : Html::a(Html::icon('remove'), ['toggle-status', 'id' => $model->id, 'attribute' => 'is_hide'], ['class' => 'text-danger', 'data-ajax' => '1', 'data-ajax-method' => 'post', 'data-ajax-pjax-reload' => '#pjax-page']);
            },
            'format' => 'raw',
        ],
        [
            'header' => 'แก้ไข',
            'class' => ActionColumn::className(),
            'template' => '{update}',
            'buttons' => [
                'update' => function($url) {
                    return Html::a(Html::icon('pencil'), $url, ['data-modal' => '1', 'data-pjax' => '0']);
                },
            ],
            'headerOptions' => [
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
<?php Panel::end(); ?>
<?php Pjax::end(); ?>
<?php

$this->registerJs(<<<JS
        $(document).ready(function(){
            $("#author-search-text").select();
        });
JS
)?>