<?php

use app\modules\admin\components\Html;
use kartik\form\ActiveForm;
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
<?php foreach ($manager->attributeInputs() as $attribute => $input): ?>
    <?= $form->field($manager, $attribute)->textInput(); ?>
<?php endforeach; ?>
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