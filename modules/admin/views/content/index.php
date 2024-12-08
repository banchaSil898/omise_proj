<?php

use app\components\grid\OrderButtonColumn;
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

Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?php

Panel::begin([
    'header' => Html::encode($this->context->title) . ' <small>' . Html::encode($this->context->subtitle) . '</small>',
    'icon' => 'star',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มเนื้อหา',
            'url' => ['create'],
            'linkOptions' => [
                'class' => 'btn btn-primary',
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
            'class' => CheckboxColumn::className(),
        ],
        [
            'label' => '',
            'attribute' => 'icon',
            'value' => function($model) {
                return $model->icon ? Html::tag('span', '', ['class' => 'fa ' . $model->icon]) : '';
            },
            'format' => 'raw',
            'visible' => $this->context->id === 'article',
            'headerOptions' => [
                'width' => 48,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'name',
        ],
        [
            'class' => OrderButtonColumn::className(),
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
<?php Panel::end(); ?>
<?php Pjax::end(); ?>
<?php

$this->registerJs(<<<JS
        $(document).ready(function(){
            $("#author-search-text").select();
        });
JS
)?>