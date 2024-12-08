<?php

use app\components\Html;
use kartik\form\ActiveForm;
use kartik\widgets\ColorInput;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\redactor\widgets\Redactor;
use yii\web\JsExpression;
use yiister\gentelella\widgets\Panel;
?>
<?php
Panel::begin([
    'header' => Html::encode($this->context->title) . ' <small>' . Html::encode($this->context->subtitle) . '</small>',
    'icon' => 'globe',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('arrow-left') . ' ย้อนกลับ',
            'url' => Url::previous('content') ? Url::previous('content') : ['index'],
            'linkOptions' => [
                'class' => 'btn btn-default',
            ],
        ],
        [
            'encode' => false,
            'label' => Html::icon('floppy-save') . ' บันทึกข้อมูล',
            'linkOptions' => [
                'class' => 'btn btn-save btn-primary',
            ],
        ]
    ],
]);
?>
<?php
$form = ActiveForm::begin([
            'id' => 'content-frm',
            'type' => 'horizontal',
        ])
?>
<?php if ($this->context->id === 'article'): ?>
    <?=
    $form->field($model, 'icon')->widget(Select2::className(), [
        'data' => Html::awesomeList(),
        'pluginOptions' => [
            'width' => 180,
            'templateSelection' => new JsExpression(<<<JS
                    function(data){
                        return $('<span class="fa '+ data.text +'"></span>');
                    }
JS
            ),
            'templateResult' => new JsExpression(<<<JS
                    function(data){
                        return $('<span class="fa '+ data.text +'"></span>'); 
                    }
JS
            ),
        ],
    ]);
    ?>
<?php endif; ?>
<?= $form->field($model, 'name')->textInput(); ?>
<?= $form->field($model, 'url_key')->textInput(); ?>
<?=
$form->field($model, 'brief')->textArea([
    'rows' => 6,
]);
?>
<?=
$form->field($model, 'description')->widget(Redactor::className(), [
    'clientOptions' => [
        'imageManagerJson' => ['/redactor/upload/image-json'],
        'imageUpload' => ['/redactor/upload/image'],
        'fileUpload' => ['/redactor/upload/file'],
        'plugins' => ['clips', 'fontcolor', 'imagemanager', 'video', 'table']
    ]
]);
?>
<?php if (in_array($this->context->id, ['news'])): ?>
    <div class="form-group">
        <div class="col-md-10 col-md-offset-2">
            <div id="background-photo">
                <?php if ($model->background_url) : ?>
                    <?= Html::img($model->backgroundUrl, ['class' => 'img-resp']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?=
    $form->field($model, 'background_file')->label('รูปปก')->widget(FileInput::classname(), [
        'options' => [
            'id' => 'background_file',
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'uploadUrl' => Url::to(['upload-file']),
            'showPreview' => false,
            'showUpload' => true,
        ],
    ]);
    ?>
    <?= $form->field($model, 'background_url')->label(false)->hiddenInput(); ?>
<?php endif; ?>
<div class="modal-footer">
    <?= Html::submitButton(Html::icon('floppy-save') . ' บันทึกข้อมูล', ['class' => 'btn btn-primary']); ?>
</div>
<?php ActiveForm::end(); ?>
<?php Panel::end(); ?>
<?php
$this->registerJs(<<<JS
    $('#background_file').on('fileselect', function(event, numFiles, label) {
        $('#background_file').fileinput('upload');
    });
        
    $('#background_file').on('fileuploaded', function(event, data, previewId, index) {
        $('#background-photo').html(data.response.html);
        $('#content-background_url').val(data.response.name);
    });

    $('.btn-save').on("click", function(){
        $('#content-frm').submit();
        return false;
    });

JS
);
?>