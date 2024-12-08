<?php

use app\models\Bank;
use app\widgets\Page;
use codesk\components\Html;

$banks = Bank::find()->active()->all();
?>
<?php $this->beginContent('@app/views/site/layouts/content.php'); ?>
<div class="row">
    <div class="col-sm-6">
        <?php
        Page::begin([
            'title' => 'ชำระด้วยวิธีโอนเงิน',
        ]);
        ?>
        <p><?= Html::awesome('question'); ?> <?= Html::a('ขั้นตอนการชำระเงินโดยการโอนเงินผ่านธนาคาร ( เคาน์เตอร์ธนาคาร, ATM, iBanking)', ['site/term-transfer']); ?></p>
        <p class="text-primary">* อย่าโอนเงินจนกว่าจะได้รับเลขที่ใบสั่งซื้อ *</p>
        <p>กรณีการโอนเงิน กรุณาดำเนินการชำระเงินภายใน 3 วัน <br/>และแจ้งการโอนเงินพร้อมแนบหลักฐานมาที่ <?= Html::mailto('matichonbook@matichon.co.th'); ?></p>
        <h4>**การแจ้งโอนเงิน</h4>
        <p>เพื่อความรวดเร็วในการดำเนินการกรุณาแนบหลักฐานการโอนเงินเข้ามาด้วยนะคะ<br/>หากลูกค้าไม่ได้รับอีเมล์ตอบกลับหลังจากแจ้งการโอนเงินภายใน 2 วัน<br/>กรุณาติดต่อกลับมาที่ โทร. 0-2589-0020 ต่อ 3350-51 (วันจันทร์ - ศุกร์ เวลา 09.00 - 17.00 น.)</p>
        <p>ชำระเงินโดยการโอนเงินผ่านธนาคาร – ชื่อบัญชี : <span class="text-primary text-bold">บริษัท งานดี จำกัด</span></p>
        <div class="media-list">
            <?php foreach ($banks as $bank): ?>
                <div class="media-object" style="padding:10px;">
                    <div class="media-left">
                        <?= Html::img($bank->cover_url, ['class' => 'media-object', 'style' => 'width:60px']); ?>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading" style="padding-top:5px;"><span class="text-primary"><?= Html::encode($bank->name); ?></span> <small>สาขา<?= Html::encode($bank->account_branch); ?> (<?= Html::encode($bank->account_type); ?>)</small></h4>
                        <p>หมายเลขบัญชี : <?= Html::encode($bank->account_no); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php Page::end(); ?>
    </div>
    <div class="col-sm-6">
        <?php
        Page::begin([
            'title' => 'ชำระด้วยบัตรเครดิต',
        ]);
        ?>
        <p><?= Html::awesome('question'); ?> <?= Html::a('ขั้นตอนการชำระเงินด้วยบัตรเครดิต VISA CARD / MASTER CARD', ['site/term-credit']); ?></p>
        <p>ทางฝ่ายจะทำการตัดบัตรในเวลา 15.00 น. ของทุกวันจันทร์-ศุกร์ และจะจัดส่งในวันถัดไป</p>
        <p>(หยุดทำการเสาร์-อาทิตย์) หากเลยเวลา 15.00 น. ทางฝ่ายจะดำเนินการตัดบัตรในวันถัดไป</p>
        <h4>สอบถามข้อมูลเพิ่มเติม</h4>
        <p>หากมีข้อสงสัย หรือต้องการสอบถามข้อมูลเพิ่มเติม กรุณาติดต่อมาที่ <br/>ฝ่ายขายสำนักพิมพ์มติชน หรือ โทร. 02-589-0020 ต่อ 3350-51 (จันทร์-ศุกร์ เวลา 9:00-17:00)</p>
        <?php Page::end(); ?>
    </div>
</div>
<?php $this->endContent(); ?>