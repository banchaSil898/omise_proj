<?php

use app\widgets\Breadcrumbs;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'ช่องทางการชำระเงิน',
            'url' => ['site/term'],
        ],
        [
            'label' => 'แจ้งการโอนเงิน',
            'url' => ['transfer/index'],
        ],
        [
            'label' => 'ตรวจสอบสถานะ',
            'url' => ['order/index'],
        ],
        [
            'label' => 'วิธีจัดส่ง',
            'url' => ['site/delivery'],
        ]
    ],
]);
?>
<div class="container">
    <?= $content; ?>
</div>