<?php

use codesk\components\Html;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\Url;
?>
<?php
$this->beginContent('@module/views/product/layouts/form.php', [
    'model' => $model,
]);
?>
<?php
$form = ActiveForm::begin([
            'type' => 'horizontal',
        ])
?>
<?=
$form->field($model, 'ebook_file')->widget(FileInput::classname(), [
    'options' => [
        'id' => 'ebook_file',
        'accept' => 'application/pdf'
    ],
    'pluginOptions' => [
        'uploadUrl' => Url::to(['upload-ebook', 'id' => $model->id]),
        'showPreview' => false,
        'showUpload' => true,
    ],
]);
?>
<?= $form->field($model, 'ebook_name')->label(false)->hiddenInput() ?>
<div class="form-group">
    <div class="col-md-10 col-md-offset-2">
        <div id="resp-box">
            <?php if ($model->ebook_name): ?>
                <iframe class="pdf-box" src="<?= $model->ebookUrl; ?>" width="100%" height="600"></iframe>
            <?php else: ?>
                <div class="well well-sm">
                    กรุณาอัพโหลดไฟล์ PDF
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
<?php
$this->registerJs(<<<JS
    $('#ebook_file').on('fileselect', function(event, numFiles, label) {
        $('#ebook_file').fileinput('upload');
    });
        
    $('#ebook_file').on('fileuploaded', function(event, data, previewId, index) {
        $('#product-ebook_name').val(data.response.filename);
        
        var ifrm = document.createElement("iframe");
        ifrm.setAttribute("src", data.response.url);
        ifrm.class = "pdf-box";
        ifrm.style.width = "100%";
        ifrm.style.height = "600px";
        $('#resp-box').html(ifrm);
    });
JS
)?>