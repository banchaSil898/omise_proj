<?php

use app\models\Bank;
use codesk\components\Html;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div style="margin-top:30px;">
    <p>หลังจากชำระเงินเรียบร้อยแล้ว กรุณาแจ้งโอนเงิน</p>
    <?php
    $form = ActiveForm::begin([
                'type' => 'horizontal',
                'enableClientValidation' => false,
                'formConfig' => [
                    'labelSpan' => 3,
                ]
    ]);
    ?>
    <?= $form->field($model, 'transfer_bank_origin')->dropDownList(ArrayHelper::map(Bank::find()->andWhere(['is_enabled' => '1'])->orderBy(['order_no' => SORT_ASC])->all(), 'id', 'shortName')); ?>
    <?= $form->field($model, 'transfer_bank')->textInput([]); ?>
    <?=
    $form->field($model, 'transfer_date')->widget(DatePicker::className(), [
        'pluginOptions' => [
            'format' => 'yyyy-M-dd',
        ]
    ]);
    ?>
    <?= $form->field($model, 'transfer_time')->textInput(); ?>
    <?= $form->field($model, 'transfer_amount')->textInput([]); ?>
    <?= $form->field($model, 'transferFile')->fileInput([]); ?>
    <div>
        <?= Html::submitButton('แจ้งโอนเงิน', ['class' => 'btn btn-primary pull-right btn-lg']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>