<?php

use codesk\components\Html;
use yii\bootstrap\ActiveForm;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'ajax-frm',
            'layout' => 'horizontal',
            'enableAjaxValidation' => true,
            'validationUrl' => $model->isNewRecord ? ['create', 'id' => $model->folder_id, 'mode' => 'validate'] : ['update', 'id' => $model->id, 'mode' => 'validate'],
            'options' => [
                'data' => [
                    'ajax-form' => '1',
                    'ajax-pjax-reload' => '#pjax-page',
                ]
            ],
        ]);
?>
<div class="modal-header bg-primary">
    <?= Html::close(); ?>
    <h4 class="modal-title">ข้อมูลหมวดหมู่ย่อย</h4>
</div>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(); ?>
</div>
<div class="modal-footer">
    <?= Html::submitButton(Html::icon('floppy-save') . ' บันทึกข้อมูล', ['class' => 'btn btn-primary']); ?>
</div>
<?php ActiveForm::end(); ?>