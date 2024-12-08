<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\widgets\ColorInput;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use yii\redactor\widgets\Redactor;
use yiister\gentelella\widgets\Panel;
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
$form->field($model, 'html')->widget(Redactor::className(), [
    'clientOptions' => [
        'imageManagerJson' => ['/redactor/upload/image-json'],
        'imageUpload' => ['/redactor/upload/image'],
        'fileUpload' => ['/redactor/upload/file'],
        'plugins' => ['clips', 'fontcolor', 'fontsize', 'imagemanager']
    ]
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