<?php

use app\components\Html;
use app\widgets\Breadcrumbs;
use app\widgets\Page;
use kartik\form\ActiveForm;
?>
<?=
Breadcrumbs::widget([
    'enableSearch' => false,
    'items' => [
        [
            'label' => 'แจ้งการโอนเงิน',
            'url' => ['transfer/index'],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => 'แจ้งการโอนเงิน',
    ]);
    ?>
    <?php
    $form = ActiveForm::begin([
                'type' => 'inline',
    ]);
    ?>
    <?=
    $form->field($model, 'purchase_no', [
        'autoPlaceholder' => false,
        'addon' => [
            'prepend' => [
                'content' => 'เลขที่ใบสั่งซื้อ',
            ],
        ],
    ])->textInput([
        'autoFocus' => true,
    ]);
    ?>
    <?= Html::submitButton(Html::icon('search') . ' ค้นหาข้อมูล', ['class' => 'btn btn-primary']); ?>
    <br/><br/>
    <div class="text-danger">
        <?=
        Html::errorSummary($model, [
            'header' => '',
        ]);
        ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Page::end(); ?>
</div>