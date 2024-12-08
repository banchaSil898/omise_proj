<?php

use app\models\Country;
use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'ajax-frm',
            'enableAjaxValidation' => true,
            'validationUrl' => $address->isNewRecord ? ['address-create', 'mode' => 'validate', 'id' => $address->member_id] : ['address-update', 'member_id' => $address->member_id, 'address_id' => $address->address_id, 'mode' => 'validate'],
            'options' => [
                'data' => [
                    'ajax-form' => '1',
                    'ajax-pjax-reload' => '#pjax-page',
                ]
            ],
            'formConfig' => [
                'labelSpan' => 4,
            ],
        ]);
?>
<div class="modal-header">
    <h3 class="modal-title">ข้อมูลที่อยู่</h3>
</div>
<div class="modal-body">
    <?= $form->field($address, 'firstname')->textInput(); ?>
    <?= $form->field($address, 'lastname')->textInput(); ?>
    <?= $form->field($address, 'company_name')->textInput(); ?>
    <?= $form->field($address, 'tax_code')->textInput(); ?>
    <?= $form->field($address, 'home_no')->textInput(); ?>
    <?= $form->field($address, 'soi')->textInput(); ?>
    <?= $form->field($address, 'street')->textInput(); ?>
    <?= $form->field($address, 'province')->textInput(); ?>
    <?= $form->field($address, 'amphur')->textInput(); ?>
    <?= $form->field($address, 'tambon')->textInput(); ?>
    <?= $form->field($address, 'postcode')->textInput(); ?>
    <?= $form->field($address, 'additional')->textArea(); ?>
    <?=
    $form->field($address, 'country_id')->dropDownList(ArrayHelper::map(Country::find()->all(), 'country_code', 'name'), [
        'prompt' => '(เลือกประเทศ)',
    ]);
    ?>
</div>
<div class="modal-footer">
<?= Html::a('ปิด', ['#'], ['data-dismiss' => 'modal', 'class' => 'btn btn-default pull-left']); ?>
<?= Html::submitButton(Html::icon('floppy-save') . ' บันทึกข้อมูล', ['class' => 'btn btn-primary']); ?>
</div>
<?php ActiveForm::end(); ?>