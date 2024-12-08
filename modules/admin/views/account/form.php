<?php

use app\models\Account;
use app\models\Role;
use app\modules\admin\components\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<?php
$this->beginContent('@module/views/account/layouts/form.php', [
    'model' => $model,
]);
?>
<?php
$form = ActiveForm::begin([
            'type' => 'horizontal',
        ])
?>
<?= $form->field($model, 'username')->textInput(); ?>
<?=
$form->field($model, 'role_id')->dropDownList(ArrayHelper::map(Role::find()->all(), 'id', 'name'), [
    'prompt' => '(ไม่กำหนด)',
]);
?>
<?= $form->field($model, 'password')->passwordInput(); ?>
<?= $form->field($model, 'password_confirm')->passwordInput(); ?>
<hr/>
<?= $form->field($model, 'name')->textInput(); ?>
<?= $form->field($model, 'email')->textInput(); ?>
<?= $form->field($model, 'description')->textArea(); ?>

<div class="form-group text-right">
    <?= Html::submitButton(); ?>
</div>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
