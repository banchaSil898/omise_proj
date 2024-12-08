<?php

use codesk\components\Html;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\Url;
?>
<?php
$this->beginContent('@module/views/gift/layouts/form.php', [
    'model' => $model,
]);
?>
<?php
$form = ActiveForm::begin([
            'type' => 'horizontal',
        ])
?>
<div class="form-group">
    <div class="col-md-10 col-md-offset-2">
        <div class="book">
            <div class="book-cover">
                <div class="book-inner">
                    <div id="book-cover">
                        <?= Html::img($model->imageUrl, ['width' => $model->thumb_width, 'height' => $model->thumb_height]) ?>
                    </div>        
                    <div class="fade"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?=
$form->field($model, 'image_url')->widget(FileInput::classname(), [
    'options' => [
        'id' => 'image_url',
        'accept' => 'image/*'
    ],
    'pluginOptions' => [
        'uploadUrl' => Url::to(['upload-cover', 'id' => $model->id]),
        'showPreview' => false,
        'showUpload' => true,
    ],
]);
?>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
<?php
$this->registerJs(<<<JS
    $('#image_url').on('fileselect', function(event, numFiles, label) {
        $('#image_url').fileinput('upload');
    });
        
    $('#image_url').on('fileuploaded', function(event, data, previewId, index) {
        $('#book-cover').html(data.response.initialPreview[0]);
        console.log('File uploaded triggered');
        console.log(data.response.initialPreview[0]);
    });
JS
)?>