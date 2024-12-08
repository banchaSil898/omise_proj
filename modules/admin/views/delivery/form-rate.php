<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\widgets\ColorInput;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use yii\redactor\widgets\Redactor;
use yiister\gentelella\widgets\Panel;
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
<?php

Panel::begin([
    'header' => 'ตั้งค่าการจัดส่งสินค้า',
    'icon' => 'globe',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('arrow-left') . ' ย้อนกลับ',
            'url' => ['index'],
            'linkOptions' => [
                'class' => 'btn btn-default',
            ],
        ],
        [
            'encode' => false,
            'label' => Html::icon('floppy-save') . ' บันทึกข้อมูล',
            'linkOptions' => [
                'class' => 'btn btn-save btn-default',
            ],
        ]
    ],
]);
?>
<?= $form->field($model, 'name')->textInput(); ?>
<?php Panel::end(); ?>
<?php $this->endContent(); ?>
<?php ActiveForm::end(); ?>
