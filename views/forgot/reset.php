<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use yii\bootstrap\ActiveForm;
?>
<?=
Breadcrumbs::widget([
    'enableSearch' => false,
    'items' => [
        [
            'label' => 'รีเซ็ตรหัสผ่าน',
            'url' => ['/forgot/reset', 'key' => $model->account_key, 'code' => $model->forgot_code],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => 'รีเซ็ตรหัสผ่าน',
        'subtitle' => 'กรุณากรอกรหัสผ่านใหม่ สำหรับบัญชี '.Html::tag('span', $model->username, ['class' => 'text-primary']),
    ]);
    ?>
    <?php
    $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'enableClientValidation' => false,
    ]);
    ?>
    <?= $form->field($model, 'password')->label('รหัสผ่านใหม่')->passwordInput(); ?>
    <?= $form->field($model, 'password_confirm')->passwordInput(); ?>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <?= Html::submitButton('เปลี่ยนรหัสผ่าน', ['class' => 'btn btn-primary pull-right']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Page::end(); ?>
</div>
