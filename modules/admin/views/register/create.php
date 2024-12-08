<?php

use kartik\form\ActiveForm;
use yiister\gentelella\widgets\Panel;
?>
<?php

Panel::begin([
    'header' => 'ลงทะเบียนเกษตรกรใหม่',
    'icon' => 'pencil',
]);
?>
<?php

$form = ActiveForm::begin([
            'type' => 'horizontal',
        ]);
?>
<?= $form->field($model, 'idcard')->textInput(); ?>
<?= $form->field($model, 'firstname')->textInput(); ?>
<?= $form->field($model, 'lastname')->textInput(); ?>
<div class="form-group">
    <div class="col-md-10 col-md-offset-2">
        <img src="http://via.placeholder.com/150x200"/>
    </div>
</div>
<?= $form->field($model, 'photo')->fileInput(); ?>
<?= $form->field($model, 'birth_date')->textInput(); ?>
<?= $form->field($model, 'address')->textArea(['rows' => 5]); ?>
<?= $form->field($model, 'province')->dropDownList([], ['prompt' => '(เลือกจังหวัด)']); ?>
<?= $form->field($model, 'amphur')->dropDownList([], ['prompt' => '(เลือกอำเภอ)']); ?>
<?= $form->field($model, 'tambon')->dropDownList([], ['prompt' => '(เลือกตำบล)']); ?>
<?= $form->field($model, 'contact_phone')->textInput(); ?>
<?= $form->field($model, 'contact_mobile')->textInput(); ?>
<?= $form->field($model, 'contact_fax')->textInput(); ?>
<?php ActiveForm::end(); ?>
<?php Panel::end(); ?>