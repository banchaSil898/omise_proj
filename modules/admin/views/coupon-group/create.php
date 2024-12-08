<?php

use app\models\Coupon;
use app\modules\admin\components\Html;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
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
    <h4 class="modal-title">เพิ่มชุดคูปอง</h4>
</div>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(); ?>
    <?= $form->field($model, 'code_prefix')->textInput(); ?>
    <?= $form->field($model, 'code_suffix')->textInput(); ?>
    <?= $form->field($model, 'coupon_count')->textInput(); ?>
    <?= $form->field($model, 'discount_type')->dropDownList(Coupon::getDiscountTypeOptions()); ?>
    <?= $form->field($model, 'discount_value')->textInput(); ?>
    <?= $form->field($model, 'valid_date')->widget(DatePicker::className(), []); ?>
<?= $form->field($model, 'expire_date')->widget(DatePicker::className(), []); ?>
    <?= $form->field($model, 'usage_max')->textInput(); ?>
</div>
<div class="modal-footer">
<?= Html::submitButton(['class' => 'btn btn-primary']); ?>
</div>
<?php ActiveForm::end(); ?>