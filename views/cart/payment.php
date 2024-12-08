<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'ชำระเงินด้วยบัตรเครดิต',
            'url' => ['#'],
        ],
    ],
]);
?>
<div class="container">
    <div class="text-center">
        <?php
        Page::begin([
            'title' => 'กรุณารอสักครู่',
            'subtitle' => 'ระบบกำลังประมวลผล',
        ])
        ?>

        <h2 class="text-center">
            <?=
            Html::awesome('spinner', [
                'class' => 'fa-spin',
            ]);
            ?>
        </h2>
    </div>
    <?php Page::end(); ?>
    <?php
    $form = ActiveForm::begin([
                'action' => ArrayHelper::getValue(Yii::$app->params, 'kbank.gatewayUrl'),
                'options' => [
                    'id' => 'frm-payment',
                ],
    ]);
    ?>
    <?php foreach ($model->paymentFields as $name => $value): ?>
        <?= Html::hiddenInput($name, $value); ?>
    <?php endforeach; ?>
    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJs(<<<JS
        window.setTimeout(function(){
            $("#frm-payment").submit();
        },3000);
JS
);
?>