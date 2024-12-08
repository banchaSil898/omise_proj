<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
?>
<?php $this->beginContent('@app/views/site/layouts/content.php'); ?>
<?php
Page::begin([
    'title' => 'ขั้นตอนการชำระเงินโดยการโอนเงินผ่านธนาคาร ( เคาน์เตอร์ธนาคาร, ATM, iBanking)',
]);
?>

<p>สำหรับลูกค้าที่ต้องการชำระเงินผ่านธนาคาร ให้ท่านเลือก &quot;ชำระโดยการโอนเงินผ่านธนาคาร&quot; ในขั้นตอนที่ 4 ของการสั่งซื้อ ดังนี้</p>

<p>วิธีการ</p>
<ol>
    <li style="margin-bottom:30px;">เลือกวิธีการ &quot;ชำระเงินโดยการโอนเงินผ่านธนาคาร&quot; ในขั้นตอนข้อมูลการชำระเงิน
        <div>
            <?= Html::img('@web/images/payment/transfer1.jpg', ['style' => ['border' => '1px solid #ccc']]); ?>
        </div>
    </li>
    <li style="margin-bottom:30px;">ตรวจสอบรายละเอียดคำสั่งซื้อของท่านให้เรียบร้อยแล้วกด &quot;ชำระเงิน&quot;
        <div>
            <?= Html::img('@web/images/payment/transfer2.jpg', ['style' => ['border' => '1px solid #ccc']]); ?>
        </div>
    </li>
    <li style="margin-bottom:30px;">ระบบจะแจ้งผลการสั่งซื้อของท่านดังรูปด้านล่าง ลูกค้าสามารถติดตามความคืบหน้า "สถานะคำสั่งซื้อ" ของท่านได้ที่อีเมล์ที่ท่านใช้ลงทะเบียน หรือที่หน้าเว็บไซต์ www.matichonbook.com
        <div>
            <?= Html::img('@web/images/payment/transfer3.jpg', ['style' => ['border' => '1px solid #ccc']]); ?>
        </div>
    </li>
    <li style="margin-bottom:30px;">หลังจากนั้นให้ท่านเลือกชำระเงินโดยการโอนเงินผ่านช่องทางการให้บริการของธนาคาร ( เคาน์เตอร์ธนาคาร, ATM, iBanking) โดยโอนเงินเข้า ชื่อบัญชี บริษัท งานดี จำกัด ประเภทบัญชี กระแสรายวัน ดังนี้ 
        <?= $this->render('/widgets/transfer'); ?>
    </li>
</ol>
<p>เมื่อทำรายการเสร็จเรียบร้อยแล้ว ต้องทำการยืนยันการชำระเงิน ด้วยช่องทางต่างๆ ดังนี้</p>
<ol>
    <li>Website: แจ้งที่เมนู "แจ้งการโอนเงิน" ซึ่งอยู่บนเมนู ด้านบนของหน้านี้ หรือ <?= Html::a('click!', ['/transfer/index']); ?></li>
    <li>Email: อีเมล์ แจ้งรายละเอียดการโอนเงินมาที่อีเมล์ <?= Html::mailto(Yii::$app->params['adminEmail']); ?></li>
    <li>Fax แฟกซ์มาที่หมายเลข 0-2591-9012 และ 0-2591-9014 (กรุณาติดต่อกลับมาอีกครั้งเพื่อยืนยันการส่งแฟกซ์ ที่หมายเลขโทรศัพท์ 0-2589-0020 ต่อ 3350-60 *ในวันและเวลาทำการ)</li>
</ol>
<?php Page::end(); ?>
<?php $this->endContent(); ?>