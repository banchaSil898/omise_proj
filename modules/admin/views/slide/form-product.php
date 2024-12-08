<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yiister\gentelella\widgets\Panel;
$url = Url::to(['get-product-list']);
?>
<?php
Panel::begin([
    'header' => 'จัดการเนื้อหาเว็บไซต์ <small>ภาพสไลด์หน้าแรก</small>',
    'icon' => 'globe',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('arrow-left') . ' ย้อนกลับ',
            'url' => ['index'],
            'linkOptions' => [
                'class' => 'btn btn-default',
            ],
        ],
    ],
]);
?>
<?php
$form = ActiveForm::begin([
            'type' => 'horizontal',
        ])
?>
<?=
$form->field($model, 'product_id')->widget(Select2::className(), [
    'options' => [
        'placeholder' => 'เลือกสินค้า ...',
    ],
    'pluginOptions' => [
        'ajax' => [
            'url' => $url,
            'dataType' => 'json',
            'data' => new JsExpression('
                    function(params) {
                        return {
                            q : params.term,
                            page : params.page || 1
                        }; 
                    }
                ')
        ],
        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        'templateResult' => new JsExpression('function(data) { return data.text; }'),
        'templateSelection' => new JsExpression('function (data) { return data.text; }'),
    ],
]);
?>
<div class="form-group">
    <div class="col-md-10 col-md-offset-2">
        <div id="image-preview">
            <?= Html::img($model->imageUrl, ['class' => 'img-resp']); ?>
        </div>
    </div>
</div>
<?=
$form->field($model, 'image_file')->widget(FileInput::classname(), [
    'options' => [
        'id' => 'image_file',
        'accept' => 'image/*'
    ],
    'pluginOptions' => [
        'uploadUrl' => Url::to(['upload-file']),
        'showPreview' => false,
        'showUpload' => true,
    ],
]);
?>
<?= $form->field($model, 'image_url')->label(false)->hiddenInput(); ?>
<div class="modal-footer">
    <?= Html::submitButton(Html::icon('floppy-save') . ' บันทึกข้อมูล', ['class' => 'btn btn-primary']); ?>
</div>
<?php ActiveForm::end(); ?>
<?php Panel::end(); ?>
<?php
$this->registerJs(<<<JS
    $('#image_file').on('fileselect', function(event, numFiles, label) {
        $('#image_file').fileinput('upload');
    });
        
    $('#image_file').on('fileuploaded', function(event, data, previewId, index) {
        $('#image-preview').html(data.response.html);
        $('#slide-image_url').val(data.response.name);
    });
JS
);
?>