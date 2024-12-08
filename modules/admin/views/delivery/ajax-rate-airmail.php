<?php

use app\models\Country;
use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'ajax-frm',
            'type' => 'horizontal',
            'enableAjaxValidation' => true,
            'validationUrl' => $model->isNewRecord ? ['create', 'mode' => 'validate'] : ['update', 'delivery_id' => $model->delivery_id, 'weight' => $model->weight, 'mode' => 'validate', 'country_id' => $model->country_id],
            'options' => [
                'data' => [
                    'ajax-form' => '1',
                    'ajax-pjax-reload' => '#pjax-airmail',
                ]
            ],
        ]);
?>
<div class="modal-header">
    <h3 class="modal-title">ข้อมูลค่าจัดส่ง</h3>
</div>
<div class="modal-body">
    <?= $form->field($model, 'country_id')->dropDownList(['*' => '(Default Country)'] + ArrayHelper::map(Country::find()->orderBy(['iso_alpha3' => SORT_ASC])->all(), 'iso_alpha3', 'name')); ?>
    <?= $form->field($model, 'weight')->textInput(); ?>
    <?= $form->field($model, 'fee')->textInput(); ?>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', ['#'], ['data-dismiss' => 'modal', 'class' => 'btn btn-default pull-left']); ?>
    <?= Html::submitButton(Html::icon('floppy-save') . ' บันทึกข้อมูล', ['class' => 'btn btn-primary']); ?>
</div>
<?php ActiveForm::end(); ?>