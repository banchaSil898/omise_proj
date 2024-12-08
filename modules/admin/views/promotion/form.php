<?php

use app\models\Promotion;
use app\modules\admin\components\Html;
use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\SwitchInput;
?>
<?php
$this->beginContent('@module/views/promotion/layouts/form.php', [
    'model' => $model,
]);
?>
<?php
$form = ActiveForm::begin([
            'type' => 'horizontal',
        ])
?>
<?= $form->field($model, 'name')->textInput(); ?>
<?=
$form->field($model, 'promotion_type')->dropDownList(Promotion::getTypeOptions(), [
    'prompt' => '(กรุณาเลือก)',
]);
?>
<?=
$form->field($model, 'date_start')->widget(DateTimePicker::className(), [
    'model' => $model,
    'attribute' => 'date_start',
    'type' => DatePicker::TYPE_INPUT,
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd HH:ii:00',
    ],
]);
?>
<?=
$form->field($model, 'date_end')->widget(DateTimePicker::className(), [
    'model' => $model,
    'attribute' => 'date_end',
    'type' => DatePicker::TYPE_INPUT,
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd HH:ii:00',
    ],
]);
?>
<?= $form->field($model, 'is_once')->widget(SwitchInput::className()); ?>
<?= $form->field($model, 'is_final')->widget(SwitchInput::className()); ?>
<hr/>
<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
        <?=
        Html::submitButton([
            'class' => 'pull-right',
        ]);
        ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
