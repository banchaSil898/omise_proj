<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
?>
<?=
Breadcrumbs::widget([
    'enableSearch' => false,
    'items' => [
        [
            'label' => 'บัญชีของฉัน',
            'url' => ['/my/index'],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => 'บัญชีของฉัน',
    ]);
    ?>
    <?php NavBar::begin(); ?>
    <?=
    Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            [
                'label' => 'ข้อมูลบัญชี',
                'url' => ['my/index'],
            ],
            [
                'label' => 'รายการสั่งซื้อ',
                'url' => ['my/order'],
            ],
            [
                'label' => 'จัดการที่อยู่',
                'url' => ['my/address'],
            ],
            [
                'label' => 'เปลี่ยนรหัสผ่าน',
                'url' => ['my/change-password'],
            ],
        ],
    ]);
    ?>
    <?php NavBar::end(); ?>
    <?= $content; ?>
    <?php Page::end(); ?>
</div>