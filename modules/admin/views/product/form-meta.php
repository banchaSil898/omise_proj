<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

$form = new ActiveForm;
?>
<?php

$this->beginContent('@module/views/product/layouts/form.php', [
    'model' => $model,
]);
?>
<?php

$form = ActiveForm::begin([
            'type' => 'horizontal',
            'enableClientValidation' => false,
        ])
?>
<?=

$form->field($model, 'meta_keywords')->widget(Select2::classname(), [
    'language' => 'th',
    'hideSearch' => true,
    'options' => [
        'multiple' => true,
    ],
    'pluginOptions' => [
        'tags' => true,
    ],
]);
?>
<?= $form->field($model, 'meta_description')->textArea(['rows' => 8]); ?>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>