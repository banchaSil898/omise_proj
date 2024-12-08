<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'ajax-frm',
            'type' => 'horizontal',
            'enableAjaxValidation' => true,
            'validationUrl' => $model->isNewRecord ? ['create', 'id' => $model->delivery_id, 'mode' => 'validate'] : ['update', 'delivery_id' => $model->delivery_id, 'weight' => $model->weight, 'mode' => 'validate'],
            'options' => [
                'data' => [
                    'ajax-form' => '1',
                    'ajax-pjax-reload' => '#pjax-page',
                ]
            ],
        ]);
?>
<div class="modal-header">
    <h3 class="modal-title">ข้อมูลค่าจัดส่ง</h3>
</div>
<div class="modal-body">
    <?= $form->field($model, 'weight')->textInput(); ?>
    <?= $form->field($model, 'fee')->textInput(); ?>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', ['#'], ['data-dismiss' => 'modal', 'class' => 'btn btn-default pull-left']); ?>
    <?= Html::submitButton(Html::icon('floppy-save') . ' บันทึกข้อมูล', ['class' => 'btn btn-primary']); ?>
</div>
<?php ActiveForm::end(); ?>