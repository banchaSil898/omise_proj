<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
?>
<?=
Breadcrumbs::widget([
    'enableSearch' => false,
    'items' => [
        [
            'label' => 'สมัครสมาชิก',
            'url' => ['/register/index'],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => 'สมัครสมาชิก',
    ]);
    ?>
    <?php
    $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'layout' => 'horizontal',
    ]);
    ?>
    <?=
    $form->field($model, 'firstname')->textInput([
        'autofocus' => true,
    ]);
    ?>
    <?= $form->field($model, 'lastname')->textInput(); ?>
    <?=
    $form->field($model, 'birth_date')->widget(DatePicker::className(), [
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
        ],
    ]);
    ?>
	<?= $form->field($model, 'phone')->textInput(); ?>
    <?= $form->field($model, 'username')->textInput(); ?>
    <?= $form->field($model, 'password')->passwordInput(); ?>
    <?= $form->field($model, 'password_confirm')->passwordInput(); ?>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <?= Html::submitButton(Html::awesome('pencil') . ' ยืนยันการสมัครสมาชิก', ['class' => 'btn btn-primary pull-right']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Page::end(); ?>
</div>