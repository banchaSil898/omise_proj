<?php

use app\modules\admin\components\Html;
use kartik\widgets\ActiveForm;
use richardfan\widget\JSRegister;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'ajax-frm',
            'type' => 'horizontal',
            'enableClientValidation' => false,
            'fieldConfig' => [
                'labelSpan' => 3,
            ],
        ]);
?>
<div class="modal-header">
    <?= Html::close(); ?>
    <h4 class="modal-title">รายละเอียด Add-On</h4>
</div>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(); ?>
    <?=
    $form->field($model, 'price', [
        'addon' => [
            'append' => [
                'content' => 'บาท',
            ],
        ],
    ])->textInput();
    ?>
</div>
<div class="modal-footer">
    <?= Html::submitButton(); ?>
    <?= Html::a('ปิด', 'javascript:void(0);', ['data-dismiss' => 'modal', 'class' => 'btn btn-default']); ?>
</div>
<?php ActiveForm::end(); ?>
<?php JSRegister::begin(); ?>
<script>
    $('#ajax-frm').ajaxForm({
        target: '#base-modal .modal-content'
    });
</script>
<?php JSRegister::end(); ?>