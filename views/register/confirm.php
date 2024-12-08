<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use yii\bootstrap\ActiveForm;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'สมัครสมาชิก',
            'url' => ['/register/index'],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => 'ยืนยันการสมัครสมาชิก',
    ]);
    ?>
    <p>ระบบได้ส่งจดหมายยืนยันการสมัครสมาชิกไปยัง <span class="text-primary"><?= Html::encode($email); ?></span> กรุณาตรวจสอบกล่องจดหมายของคุณ</p>
        <?php Page::end(); ?>
</div>