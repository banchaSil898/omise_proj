<?php

use app\components\grid\DeleteButtonColumn;
use app\components\grid\OrderButtonColumn;
use app\components\grid\UpdateButtonColumn;
use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
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
    'header' => 'จัดการเนื้อหาเว็บไซต์ <small>ภาพสไลด์หน้าแรก</small>',
    'icon' => 'globe',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('link') . ' เพิ่มลิงค์เชื่อมโยง',
            'url' => ['link-create'],
            'linkOptions' => [
                'class' => 'btn btn-save btn-primary',
                'data-pjax' => '0',
            ],
        ],
        [
            'encode' => false,
            'label' => Html::icon('globe') . ' เพิ่มหน้า HTML',
            'url' => ['html-create'],
            'linkOptions' => [
                'class' => 'btn btn-save btn-primary',
                'data-pjax' => '0',
            ],
        ],
        [
            'encode' => false,
            'label' => Html::awesome('cube') . ' เพิ่มหน้าแสดงสินค้า',
            'url' => ['product-create'],
            'linkOptions' => [
                'class' => 'btn btn-save btn-primary',
                'data-pjax' => '0',
            ],
        ],
        [
            'encode' => false,
            'label' => Html::awesome('cubes') . ' เพิ่มหน้าแสดงชุดสินค้า',
            'url' => ['bundle-create'],
            'linkOptions' => [
                'class' => 'btn btn-save btn-primary',
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
            'class' => OrderButtonColumn::className(),
            'contentOptions' => [
                'style' => 'vertical-align:middle',
            ],
        ],
        [
            'headerOptions' => [
                'width' => 180,
            ],
            'attribute' => 'image_url',
            'value' => function($model) {
                return Html::img($model->imageUrl, ['class' => 'img-resp']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'slideType',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
                'style' => 'vertical-align:middle',
            ],
        ],
        [
            'attribute' => 'description',
            'contentOptions' => [
                'style' => 'vertical-align:middle',
            ],
        ],
        [
            'class' => UpdateButtonColumn::className(),
        ],
        [
            'class' => DeleteButtonColumn::className(),
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