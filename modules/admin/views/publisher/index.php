<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
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
    'header' => 'จัดการข้อมูลสำนักพิมพ์',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มสำนักพิมพ์',
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
    'responsiveWrap' => false,
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
                'class' => 'text-center',
                'style' => 'width:48px;'
            ],
            'contentOptions' => [
                'style' => 'padding:1px;',
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'name',
        ],
        [
            'label' => 'จำนวนสินค้า',
            'value' => function($model) {
                return Html::a($model->getProducts()->count(), ['product/index', 'Product' => [
                                'publisher_id' => $model->id,
                ]]);
            },
            'format' => 'raw',
            'headerOptions' => [
                'width' => 120,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'label' => 'เครือมติชน',
            'attribute' => 'is_own',
            'value' => function($model) {
                return $model->is_own ? Html::icon('ok', ['class' => 'text-success']) : Html::icon('remove', ['class' => 'text-danger']);
            },
            'format' => 'html',
            'headerOptions' => [
                'width' => 140,
                'class' => 'text-center',
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