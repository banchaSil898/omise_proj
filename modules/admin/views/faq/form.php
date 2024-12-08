<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\redactor\widgets\Redactor;
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
    <h3 class="modal-title">ข้อมูลสอบถาม</h3>
</div>
<div class="modal-body">
    <?=
    $form->field($model, 'name')->textInput([]);
    ?>
    <?=
    $form->field($model, 'answer')->widget(Redactor::className(), [
        'clientOptions' => [
            'imageManagerJson' => ['/redactor/upload/image-json'],
            'imageUpload' => ['/redactor/upload/image'],
            'fileUpload' => ['/redactor/upload/file'],
            'plugins' => ['clips', 'fontcolor', 'imagemanager']
        ]
    ]);
    ?>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', ['#'], ['data-dismiss' => 'modal', 'class' => 'btn btn-default pull-left']); ?>
    <?= Html::submitButton(Html::icon('floppy-save') . ' บันทึกข้อมูล', ['class' => 'btn btn-primary']); ?>
</div>
<?php ActiveForm::end(); ?>
