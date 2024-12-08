<?php

use app\widgets\Page;
use codesk\components\Html;
?>
<?php $this->beginContent('@app/views/site/layouts/content.php'); ?>
<?php
Page::begin([
    'title' => 'วิธีจัดส่ง',
]);
?>
<h4 class="text-primary">ค่าจัดส่ง</h4>
<p>ค่าจัดส่งตามจริง</p>
<ul>
    <li>ค่าจัดส่งเริ่มต้นที่ 40 บาท</li>
    <li>ค่าจัดส่งแบบ EMS คิดตามราคาน้ำหนักของสินค้า</li>
</ul>

<hr/>
<h4 class="text-primary">จัดส่งด้วยวิธีใด</h4>

<div style="padding-left:20px;">
    <h4 class="text-bold">กรุงเทพฯ – ปทุมธานี – นนทบุรี – สมุทรปราการ</h4>
    <ul>
        <li>จัดส่งโดยบริษัทเอกชน โดยจะมีเจ้าหน้าที่ขนส่งโทรศัพท์ติดต่อลูกค้าเพื่อแจ้งล่วงหน้าในวันที่จัดส่ง</li>
        <li>จัดส่งโดยไปรษณีย์ไทย</li>
    </ul>
    <h4 class="text-bold">ต่างจังหวัด</h4>
    <ul>
        <li>จัดส่งโดยไปรษณีย์ไทย</li>
    </ul>
</div>
<h4 class="text-primary">สอบถามข้อมูลเพิ่มเติม</h4>
<div style="padding-left:20px;">
    <p>หากมีข้อสงสัย หรือต้องการสอบถามข้อมูลเพิ่มเติม กรุณาติดต่อมาที่ <?= Html::mailto('matichonbook@matichon.co.th'); ?></p>
    <p>หรือ ติดต่อฝ่ายขาย โทร. 02-589-0020 ต่อ 3350-3351 (จันทร์-ศุกร์ เวลา 9:00-17:00)</p>
</div>
<?php Page::end(); ?>
<?php $this->endContent(); ?>