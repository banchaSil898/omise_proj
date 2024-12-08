<?php

use app\components\grid\DeleteButtonColumn;
use app\components\grid\UpdateButtonColumn;
use codesk\components\Html;
use kartik\form\ActiveForm;
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
    'header' => 'การแปลงคำค้น',
    'icon' => 'random',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มคำ',
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
            'attribute' => 'word_from',
        ],
        [
            'attribute' => 'word_to',
        ],
        [
            'class' => UpdateButtonColumn::className(),
            'buttonOptions' => [
                'data-modal' => 1,
                'data-pjax' => 0,
            ],
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