<?php

use app\models\Publisher;
use codesk\components\Html;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use yii\helpers\Url;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'ajax-frm',
            'type' => 'horizontal',
            'enableAjaxValidation' => true,
            'validationUrl' => $model->isNewRecord ? ['create', 'mode' => 'validate'] : ['update', 'id' => $model->id, 'mode' => 'validate'],
            'options' => [
                'data' => [
                    'ajax-form' => '1',
                    'ajax-pjax-reload' => '#pjax-page',
                ]
            ],
        ]);
?>
<div class="modal-header">
    <h3 class="modal-title">ข้อมูลสำนักพิมพ์</h3>
</div>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(); ?>
    <div class="form-group">
        <div class="col-md-10 col-md-offset-2">
            <div id="publisher-photo">
                <?= Html::img($model->photoUrl ? $model->photoUrl : Html::placeholder(Publisher::PHOTO_WIDTH, Publisher::PHOTO_HEIGHT), ['width' => Publisher::PHOTO_WIDTH, 'height' => Publisher::PHOTO_HEIGHT, 'class' => 'publisher-photo']); ?>
            </div>
        </div>
    </div>
    <?=
    $form->field($model, 'photo_file')->widget(FileInput::classname(), [
        'options' => [
            'id' => 'photo_file',
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'uploadUrl' => Url::to(['upload-file']),
            'showPreview' => false,
            'showUpload' => true,
        ],
    ]);
    ?>
    <?= $form->field($model, 'photo_url')->label(false)->hiddenInput(); ?>
    <?= $form->field($model, 'is_own')->widget(SwitchInput::className()); ?>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', ['#'], ['data-dismiss' => 'modal', 'class' => 'btn btn-default pull-left']); ?>
    <?= Html::submitButton(Html::icon('floppy-save') . ' บันทึกข้อมูล', ['class' => 'btn btn-primary']); ?>
</div>
<?php ActiveForm::end(); ?>
<?php
$this->registerJs(<<<JS
    $('#photo_file').on('fileselect', function(event, numFiles, label) {
        $('#photo_file').fileinput('upload');
    });
        
    $('#photo_file').on('fileuploaded', function(event, data, previewId, index) {
        $('#publisher-photo').html(data.response.html);
        $('#publisher-photo_url').val(data.response.name);
    });
JS
)?>