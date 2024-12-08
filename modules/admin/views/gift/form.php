<?php

use app\models\Promotion;
use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;

?>
<?php

$this->beginContent('@module/views/gift/layouts/form.php', [
    'model' => $model,
]);
?>
<?php

$form = ActiveForm::begin([
            'type' => 'horizontal',
        ])
?>
<?= $form->errorSummary($model); ?>
<?= $form->field($model, 'name')->textInput(); ?>
<?= $form->field($model, 'description')->textArea(['rows' => 6]); ?>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
