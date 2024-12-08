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
    'header' => 'จัดการคำถามที่พบบ่อย (FAQ)',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มข้อมูล',
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
            'attribute' => 'name',
        ],
        [
            'header' => 'แก้ไข',
            'class' => ActionColumn::className(),
            'template' => '{update}',
            'buttons' => [
                'update' => function($url) {
                    return Html::a(Html::icon('pencil'), $url, ['data-modal' => '1', 'data-modal-size' => 'lg', 'data-pjax' => '0']);
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