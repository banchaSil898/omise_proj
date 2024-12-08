<?php

use app\models\Contact;
use app\models\ContactType;
use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
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
    $form->field($model, 'name')->textInput([
        'disabled' => true,
    ]);
    ?>
    <?=
    $form->field($model, 'email')->textInput([
        'disabled' => true,
    ]);
    ?>
    <?= $form->field($model, 'contact_type_id')->dropDownList(ArrayHelper::map(ContactType::find()->all(), 'id', 'name')); ?>
    <?= $form->field($model, 'purchase_no')->textInput(); ?>
    <?= $form->field($model, 'description')->textArea(['rows' => 5]); ?>
    <?= $form->field($model, 'record_remark')->textArea(['rows' => 5]); ?>
    <?= $form->field($model, 'record_status')->dropDownList(Contact::getStatusOptions()); ?>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', ['#'], ['data-dismiss' => 'modal', 'class' => 'btn btn-default pull-left']); ?>
    <?= Html::submitButton(Html::icon('floppy-save') . ' บันทึกข้อมูล', ['class' => 'btn btn-primary']); ?>
</div>
<?php ActiveForm::end(); ?>
