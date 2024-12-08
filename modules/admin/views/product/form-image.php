<?php

use app\modules\admin\components\Html;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>
<?php
$this->beginContent('@module/views/product/layouts/form.php', [
    'model' => $model,
]);
?>
<?php
Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => SerialColumn::className(),
        ],
        [
            'attribute' => 'thumb_url',
            'value' => function($model) {
                return Html::a(Html::img($model->thumbUrl ? $model->thumbUrl : Html::placeholder(48, 65), ['width' => 48]), ['preview-image', 'product_id' => $model->product_id, 'image_id' => $model->image_id], ['data-pjax' => '0', 'data-modal' => 1, 'data-modal-size' => 'lg']);
            },
            'format' => 'raw',
            'headerOptions' => [
                'width' => 128,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'style' => 'padding:2px;',
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'created_at',
            'format' => 'datetime',
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
            'template' => '{delete-image}',
            'buttons' => [
                'delete-image' => function($url) {
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
                'width' => 80,
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
    ],
]);
?>
<?php Pjax::end(); ?>
<?php
$form = ActiveForm::begin([
            'type' => 'horizontal',
            'enableClientValidation' => false,
        ]);
?>
<div class="well well-sm">
    <h4 class="text-bold">เพิ่มรูปภาพ</h4>
    <?=
    $form->field($model, 'image_url')->widget(FileInput::classname(), [
        'options' => [
            'id' => 'image_url',
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'uploadUrl' => Url::to(['upload-image', 'id' => $model->id]),
            'showPreview' => false,
            'showUpload' => true,
        ],
    ]);
    ?>
</div>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
<?php
$this->registerJs(<<<JS
    $('#image_url').on('fileselect', function(event, numFiles, label) {
        $('#image_url').fileinput('upload');
    });
        
    $('#image_url').on('fileuploaded', function(event, data, previewId, index) {
        $('#book-cover').html(data.response.initialPreview[0]);
        $.pjax.reload({
            container : '#pjax-page'
        });
        console.log('File uploaded triggered');
        console.log(data.response.initialPreview[0]);
    });
JS
)?>