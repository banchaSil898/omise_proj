<?php

use codesk\components\Html;
use yii\bootstrap\ActiveForm;
?>
<?php $this->beginContent('@app/views/my/layout.php'); ?>
<?php
$form = ActiveForm::begin([
            'layout' => 'horizontal',
            'enableClientValidation' => false,
        ]);
?>
<?= $form->field($model, 'password_old')->label('รหัสผ่านเดิม')->passwordInput(); ?>
<?= $form->field($model, 'password')->label('รหัสผ่านใหม่')->passwordInput(); ?>
<?= $form->field($model, 'password_confirm')->passwordInput(); ?>
<div class="form-group">
    <div class="col-sm-6 col-sm-offset-3">
        <?= Html::submitButton('เปลี่ยนรหัสผ่าน', ['class' => 'btn btn-primary pull-right']); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
