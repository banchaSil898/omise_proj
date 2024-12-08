<?php

use app\modules\admin\components\Html;
use kartik\datetime\DateTimePicker;
use kartik\form\ActiveForm;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'ajax-frm',
            'type' => 'horizontal',
            'enableAjaxValidation' => true,
            'validationUrl' => $model->isNewRecord ? ['create', 'mode' => 'validate', 'id' => $promotion->id] : ['update', 'mode' => 'validate', 'id' => $model->id],
            'options' => [
                'data' => [
                    'ajax-form' => '1',
                    'ajax-pjax-reload' => '#pjax-page',
                ]
            ],
        ])
?>
<div class="modal-header">
    <h3 class="modal-title">ข้อมูลคูปอง สำหรับ <span class="text-primary"><?= Html::encode($promotion->name); ?></span></h3>
</div>
<div class="modal-body">
    <?= $form->field($model, 'code_prefix')->textInput(); ?>
    <?= $form->field($model, 'code_suffix')->textInput(); ?>
    <?= $form->field($model, 'coupon_count')->textInput(); ?>
    <?=
    $form->field($model, 'is_single_use')->radioButtonGroup([
        '0' => 'ใช้ซ้ำได้หลายครั้ง',
        '1' => '1 บัญชี ใช้ได้เพียง 1 ครั้ง',
    ]);
    ?>
    <?=
    $form->field($model, 'valid_date')->widget(DateTimePicker::className(), [
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii',
        ],
    ]);
    ?>
    <?=
    $form->field($model, 'expire_date')->widget(DateTimePicker::className(), [
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii',
        ],
    ]);
    ?>
    <?= $form->field($model, 'usage_max')->textInput(); ?>
    <?= $form->field($model, 'usage_current')->textInput(); ?>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', ['#'], ['data-dismiss' => 'modal', 'class' => 'btn btn-default pull-left']); ?>
    <?= Html::submitButton(); ?>
</div>
<?php ActiveForm::end(); ?>