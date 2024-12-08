<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'ชำระเงินเรียบร้อย',
            'url' => ['#'],
        ],
    ],
]);
?>
<div class="container">
    <div class="text-center">
        <?php
        Page::begin([
            'title' => 'ชำระเงินเรียบร้อย',
            'subtitle' => 'ขอบคุณที่ใช้บริการ',
        ])
        ?>
        <div class="text-center">
            <?= Html::img('@web/images/web/ok.png') ?>
        </div>
    </div>
    <div class="text-center">
        <?= Html::a(Html::awesome('arrow-left') . ' กลับไปหน้าแรก', ['site/index'], ['class' => 'btn btn-primary']); ?>
        <?= Html::a(Html::awesome('file') . ' แสดงข้อมูลใบสั่งซื้อ', ['order/view', 'order_no' => $order_no], ['class' => 'btn btn-info']); ?>
    </div>
    <?php Page::end(); ?>
</div>