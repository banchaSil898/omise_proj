<?php

use app\models\Delivery;
use kartik\form\ActiveForm;
?>
<?php

$form = ActiveForm::begin([
            'id' => 'delivery_frm',
            'type' => 'horizontal',
        ])
?>
<?php

$this->beginContent('@module/views/delivery/layouts/form.php', [
    'model' => $model,
]);
?>
<?= $form->field($model, 'name')->textInput(); ?>
<?=

$form->field($model, 'fee_type')->dropDownList(Delivery::getFeeTypeOptions(), [
    'prompt' => '(เลือกประเภทค่าธรรมเนียม)',
]);
?>
<?php $this->endContent(); ?>
<?php ActiveForm::end(); ?>

