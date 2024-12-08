<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'ไม่สามารถทำรายการชำระเงิน',
            'url' => ['#'],
        ],
    ],
]);
?>
<div class="container">
    <div class="text-center">
        <?php
        Page::begin([
            'title' => 'ไม่สามารถทำรายการชำระเงิน',
            'subtitle' => 'กรุณาติดต่อเจ้าหน้าที่',
        ])
        ?>
        <div class="text-center">
            <?= Html::img('@web/images/web/error.png') ?>
        </div>
    </div>
    <div class="text-center" style="margin-bottom: 60px;">
        <?= Html::a('แสดงข้อมูลใบสั่งซื้อ', ['order/view', 'order_no' => $order_no], ['class' => 'btn btn-info']); ?>
    </div>
    <?php Page::end(); ?>
</div>