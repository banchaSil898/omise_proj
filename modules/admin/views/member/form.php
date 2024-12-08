<?php

use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;
?>
<?php

$this->beginContent('@module/views/member/layouts/form.php', [
    'model' => $model,
]);
?>
<?php

$form = ActiveForm::begin([
            'type' => 'horizontal',
        ])
?>
<?= $form->errorSummary($model); ?>
<?= $form->field($model, 'username')->textInput(); ?>
<?= $form->field($model, 'firstname')->textInput(); ?>
<?= $form->field($model, 'lastname')->textInput(); ?>
<?=

$form->field($model, 'birth_date')->widget(DatePicker::className(), [
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
    ],
]);
?>
<?=

$form->field($model, 'facebook_id')->textInput([
    'disabled' => true,
]);
?>
<?=

$form->field($model, 'google_id')->textInput([
    'disabled' => true,
]);
?>
<?=

$form->field($model, 'magento_id')->textInput([
    'disabled' => true,
]);
?>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
