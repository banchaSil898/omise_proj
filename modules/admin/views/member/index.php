<?php

use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use richardfan\widget\JSRegister;
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
    'header' => 'จัดการข้อมูลสมาชิก',
    'icon' => 'star',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มข้อมูลสมาชิก',
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
            'id' => 'search-frm',
            'type' => 'inline',
            'action' => ['index'],
            'method' => 'get',
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
<?=

$form->field($model, 'search[date_start]', [
    'autoPlaceholder' => false,
    'inputOptions' => [
        'placeholder' => 'ตั้งแต่วันที่',
    ],
])->widget(DatePicker::className(), [
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
    ],
    'options' => [
        'placeholder' => 'ตั้งแต่วันที่',
    ],
]);
?> 
<?=

$form->field($model, 'search[date_end]', [
    'autoPlaceholder' => false,
    'inputOptions' => [
        'placeholder' => 'ถึงวันที่',
    ],
])->widget(DatePicker::className(), [
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
    ],
    'options' => [
        'placeholder' => 'ถึงวันที่',
    ],
]);
?> 
<?=

$form->field($model, 'search[ids]')->hiddenInput([
    'id' => 'item',
]);
?>
<?= Html::submitButton('ค้นหา', ['class' => 'btn btn-info btn-flat', 'name' => 'mode', 'value' => 'default']); ?>
<?= Html::submitButton(Html::icon('export') . ' ส่งออกไฟล์ XLS', ['class' => 'btn btn-success btn-flat', 'name' => 'mode', 'value' => 'xls']); ?>
<?php ActiveForm::end(); ?>
<?=

GridView::widget([
    'id' => 'data-grid',
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
            'attribute' => 'fullname',
            'value' => function($model) {
                return Html::a(Html::encode($model->fullname), ['update', 'id' => $model->id], ['data-pjax' => '0']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'username',
            'value' => function($model) {
                return Html::a(Html::encode($model->username), ['update', 'id' => $model->id], ['data-pjax' => '0']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'birth_date',
            'format' => 'date',
        ],
        [
            'label' => 'Facebook',
            'attribute' => 'facebook_id',
            'value' => function($model) {
                return isset($model->facebook_id) ? Html::a(Html::awesome('facebook')) : '';
            },
            'format' => 'html',
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 80,
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'label' => 'Google+',
            'attribute' => 'google_id',
            'value' => function($model) {
                return isset($model->google_id) ? Html::awesome('google-plus') : '';
            },
            'format' => 'html',
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 80,
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'label' => 'Line',
            'attribute' => 'line_id',
            'value' => function($model) {
                return isset($model->line_id) ? Html::awesome('comment-o') : '';
            },
            'format' => 'html',
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 80,
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'created_at',
            'format' => 'date',
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
<?php JSRegister::begin(); ?>
<script>
    $(document).ready(function () {
        $("#author-search-text").select();
    });
    $(document).on('beforeSubmit', '#search-frm', function () {
        $('#item').val($('#data-grid').yiiGridView('getSelectedRows'));
        return true;
    });
</script>
<?php JSRegister::end(); ?>