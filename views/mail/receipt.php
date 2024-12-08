<?php

use app\components\Html;
?>
<html>
    <head>
        <style type="text/css">
            body {
                font-family: "Garuda";
                font-size:10pt;
            }
            td, th {
                padding:2mm;
            }
        </style>
    </head>
    <body>
        <div align="center">
            <?= Html::img('@web/images/web/logo-receipt.png', ['style' => 'width:6cm;']); ?>
        </div>
        <div align="center">
            เลขที่ 12 ถนนเทศบาลนฤมาล หมู่บ้านประชานิเวศน์ 1 แขวงลาดยาว เขตจตุจักร กรุงเทพฯ 10900<br/>
            โทร 0-2589-0020 ต่อ 3324 FAX : 0-2580-0558<br/>
            เลขที่ผู้เสียภาษี 010 52290 06522
        </div>
        <h5 style="margin-bottom:0px;border-bottom:2px solid #ccc;padding-bottom:3px;">เลขที่ใบสั่งซื้อของท่าน #<?= $purchase->purchase_no; ?> (สั่งซื้อเมื่อ <?= Yii::$app->formatter->asDate($purchase->order_date); ?>)</h5>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <td width="50%" style="vertical-align:top;padding-right:3px;">

                    <h4 style="margin-top:1px;margin-bottom:1px;padding:0.3em 0.5em; background:#ccc;">ที่อยู่สำหรับออกใบเสร็จ</h4>
                    <div style="margin:5px;">
                        <div><?= Html::encode($purchase->invoice_firstname); ?> <?= Html::encode($purchase->invoice_lastname); ?></div>
                        <div><?= Html::encode($purchase->invoice_company); ?> <?= Html::encode($purchase->invoice_tax); ?></div>
                        <div><?= Html::encode($purchase->invoice_address); ?> </div>
                        <div>
                            <?php if ($purchase->invoice_tambon): ?>
                                แขวง/ตำบล <?= Html::encode($purchase->invoice_tambon); ?>
                            <?php endif ?>
                            <?php if ($purchase->invoice_amphur): ?>
                                เขต/อำเภอ <?= Html::encode($purchase->invoice_amphur); ?>
                            <?php endif ?>
                        </div>
                        <div><?= Html::encode($purchase->invoice_province); ?> <?= Html::encode($purchase->invoice_postcode); ?></div>
                        <div>โทร : <?= Html::encode($purchase->invoice_phone); ?></div>
                    </div>

                    <h4 style="margin-top:1px;margin-bottom:1px;padding:0.3em 0.5em; background:#ccc;">ที่อยู่สำหรับจัดส่งสินค้า</h4>
                    <div style="margin:5px;">
                        <div><?= Html::encode($purchase->delivery_firstname) ?> <?= Html::encode($purchase->delivery_lastname); ?></div>
                        <div><?= Html::encode($purchase->delivery_company) ?> <?= Html::encode($purchase->delivery_tax); ?></div>
                        <div><?= Html::encode($purchase->delivery_address) ?></div>
                        <div>
                            <?php if ($purchase->delivery_tambon): ?>
                                แขวง/ตำบล <?= Html::encode($purchase->delivery_tambon); ?>
                            <?php endif ?>
                            <?php if ($purchase->delivery_amphur): ?>
                                เขต/อำเภอ <?= Html::encode($purchase->delivery_amphur); ?>
                            <?php endif ?>
                        </div>
                        <div><?= Html::encode($purchase->delivery_province) ?> <?= Html::encode($purchase->delivery_postcode); ?></div>
                        <div>โทร : <?= Html::encode($purchase->delivery_phone) ?></div>
                    </div>
                </td>
                <td width="50%" style="vertical-align:top;padding-left:3px;">
                    <h4 style="margin-top:1px;margin-bottom:1px;padding:0.3em 0.5em; background:#ccc;">วิธีการชำระเงิน</h4>
                    <div style="margin:5px;">
                        <?= $purchase->getPaymentMethod(); ?>
                        <?= $purchase->getPaymentInfoPdf(); ?>
                    </div>
                    <h4 style="margin-top:1px;margin-bottom:1px;padding:0.3em 0.5em; background:#ccc;">วิธีการจัดส่ง</h4>
                    <div style="margin:5px;">
                        <?= $purchase->getDeliveryMethod(); ?>
                    </div>
                </td>
            </tr>
        </table>
        <h4 style="margin-top:1px;margin-bottom:1px;padding:0.3em 0.5em; background:#ccc;">รายการสินค้า</h4>

    </body>
</html>