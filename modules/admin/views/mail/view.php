<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\redactor\widgets\Redactor;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'ajax-frm',
            'type' => 'horizontal',
            'enableAjaxValidation' => true,
            'validationUrl' => ['update', 'id' => $model->id, 'mode' => 'validate'],
            'options' => [
                'data' => [
                    'ajax-form' => '1',
                    'ajax-pjax-reload' => '#pjax-page',
                ]
            ],
        ]);
?>
<div class="modal-header">
    <h4 class="modal-title"><?= Html::encode($model->mail_title); ?></h4>
</div>
<div class="modal-body">
    <?= $header->data; ?>
    <?= $model->mail_body ?>
    <?= $footer->data; ?>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', ['#'], ['data-dismiss' => 'modal', 'class' => 'btn btn-default pull-left']); ?>
</div>
<?php ActiveForm::end(); ?>
