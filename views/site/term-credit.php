<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
?>
<?php $this->beginContent('@app/views/site/layouts/content.php'); ?>
<?php
Page::begin([
    'title' => 'ขั้นตอนการชำระเงินด้วยบัตรเครดิต VISA CARD / MASTER CARD',
]);
?>

<p>การชำระเงินผ่านบัตรเครดิต เป็นอีกหนึ่งช่องทางในการรับชำระค่าสินค้า เพียงท่านถือบัตรเครดิต VISA CARD หรือ MASTER CARD ก็สามารถชำระเงินได้ทันที โดยการเลือกวิธีนี้ในขั้นตอนที่ [4] ข้อมูลการชำระเงิน ของการสั่งซื้อ ดังนี้</p>
<p>วิธีการ</p>
<ol>
    <li style="margin-bottom:30px;">เลือกวิธีการ "ชำระเงินผ่านบัตรเครดิต" ในขั้นตอนข้อมูลการชำระเงิน
        <div>
            <?= Html::img('@web/images/payment/credit1.jpg', ['style' => ['border' => '1px solid #ccc']]); ?>
        </div>
    </li>
    <li style="margin-bottom:30px;">ตรวจสอบรายละเอียดคำสั่งซื้อของท่านให้เรียบร้อยแล้วกด "ชำระเงิน"
        <div>
            <?= Html::img('@web/images/payment/credit2.jpg', ['style' => ['border' => '1px solid #ccc']]); ?>
        </div>
    </li>
    <li style="margin-bottom:30px;">
        ระบบจะนำท่านไปสู่หน้าของ ธนาคารกสิกรไทย เพื่อให้ท่านกรอกข้อมูลบัตรเครดิตของท่านตามแบบฟอร์มของธนาคารกสิกรไทย
        <div>
            <?= Html::img('@web/images/payment/credit3.png', ['style' => ['border' => '1px solid #ccc']]); ?>
        </div>
    </li>
</ol>
<p>หลังจากทำรายการที่หน้าของธนาคารกสิกรไทยสำเร็จแล้ว ระบบจะนำท่านไปกลับไปยังหน้า www.matichonbook.com พร้อมแจ้งว่าการสั่งซื้อได้เสร็จสมบูรณ์แล้ว</p>

<?php Page::end(); ?>
<?php $this->endContent(); ?>