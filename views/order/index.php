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
            'label' => 'ตรวจสอบสถานะสั่งซื้อ',
            'url' => ['/order/index'],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => 'ตรวจสอบสถานะสั่งซื้อ',
        'subtitle' => 'กรุณากรอกหมายเลขใบสั่งซื้อ',
    ]);
    ?>
    <?php
    $form = ActiveForm::begin([
                'layout' => 'horizontal',
    ]);
    ?>
    <?= $form->field($model, 'purchase_no')->textInput(['autoFocus' => true]); ?>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <?= Html::submitButton(Html::awesome('pencil') . ' ตรวจสอบข้อมูล', ['class' => 'btn btn-primary pull-right']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Page::end(); ?>
</div>