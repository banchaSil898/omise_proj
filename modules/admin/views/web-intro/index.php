<?php

use app\modules\admin\components\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use richardfan\widget\JSRegister;
use yii\helpers\Url;
use yiister\gentelella\widgets\Panel;
?>
<?php
Panel::begin([
    'header' => 'ตั้งค่าหน้า Intro',
    'icon' => 'cog',
]);
?>
<?php
$form = ActiveForm::begin([
            'type' => 'horizontal',
        ]);
?> 
<div class="form-group">
    <div class="col-md-10 col-md-offset-2">
        <div id="background-photo">
            <?php if ($model->background_url) : ?>
                <?= Html::img($model->backgroundUrl, ['class' => 'img-resp']); ?>
            <?php else: ?>
                <span class="text-muted">ไม่ได้ตั้งค่า</span>
            <?php endif; ?>
        </div>
    </div>
</div>
<hr/>
<?=
$form->field($model, 'background_file')->label('รูป Intro')->widget(FileInput::classname(), [
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
<div class="form-group">
    <div class="col-md-10 col-md-offset-2">
        <?= Html::a(Html::icon('trash') . ' ลบรุป Intro', ['delete'], ['class' => 'btn btn-danger']); ?>
        <?= Html::submitButton(['class' => 'pull-right']); ?>        
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php Panel::end(); ?>
<?php JSRegister::begin(); ?>
<script>
    $('#background_file').on('fileselect', function (event, numFiles, label) {
        $('#background_file').fileinput('upload');
    });

    $('#background_file').on('fileuploaded', function (event, data, previewId, index) {
        $('#background-photo').html(data.response.html);
        $('#intro-background_url').val(data.response.name);
    });

</script>
<?php JSRegister::end(); ?>