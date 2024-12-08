<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'ลืมรหัสผ่าน',
            'url' => ['/register/index'],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => 'ลืมรหัสผ่าน',
        'subtitle' => 'กรุณาตรวจสอบจดหมายยืนยันเพื่อรีเซ็ตรหัสผ่าน'
    ]);
    ?>
    <p>ระบบได้ส่งอีเมล์ไปยัง <span class="text-primary"><?= Html::encode($email); ?></span> เพื่อยืนยันการรีเซ็ตรหัสผ่าน. กรุณาตรวจสอบกล่องจดหมายของคุณเพื่อดำเนินการขั้นต่อไป</p>
    <p>หากไม่ได้รับอีเมล์ภายใน 5-10 นาที <?= Html::a('ลองอีกครั้ง', ['index']); ?></p>
    <?php Page::end(); ?>
</div>
