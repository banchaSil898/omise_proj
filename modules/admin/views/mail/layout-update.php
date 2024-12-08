<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\redactor\widgets\Redactor;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="modal-header">
    <h4 class="modal-title">ตั้งค่า Header / Footer</h4>
</div>
<div class="modal-body">
    <?=
    $form->field($header, 'data')->label('Header')->widget(Redactor::className(), [
        'options' => [
            'id' => 'configuration-header',
            'name' => 'header',
        ],
        'clientOptions' => [
            'imageManagerJson' => ['/redactor/upload/image-json'],
            'imageUpload' => ['/redactor/upload/image'],
            'fileUpload' => ['/redactor/upload/file'],
            'plugins' => ['clips', 'fontcolor', 'imagemanager']
        ]
    ]);
    ?>
    <?=
    $form->field($footer, 'data')->label('Footer')->widget(Redactor::className(), [
        'options' => [
            'id' => 'configuration-footer',
            'name' => 'footer',
        ],
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