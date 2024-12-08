<?php

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
?>
<?php

$this->beginContent('@module/views/gift/layouts/form.php', [
    'model' => $model,
]);
?>
<?php

$form = ActiveForm::begin([
            'type' => 'horizontal',
            'enableClientValidation' => false,
        ]);
?>
<?= $form->errorSummary($model); ?>
<?= $form->field($model, 'attr1_name')->textInput(); ?>
<?=

$form->field($model, 'attr1_data')->widget(Select2::className(), [
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [','],
    ],
    'options' => [
        'multiple' => true,
    ],
]);
?>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
