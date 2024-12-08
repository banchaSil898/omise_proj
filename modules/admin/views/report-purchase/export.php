<?php

use app\models\Purchase;

/* @var $model Purchase */
?>
<style>
    td {
        vertical-align: top;
        white-space:nowrap;
        mso-text-control: shrinktofit;
    }
</style>
<table border="1">
    <thead>
        <tr>
            <th>Order</th>
            <th>ที่อยู่ออกใบเสร็จ</th>
            <th>ที่อยู่ส่งสินค้า</th>
            <th>รายการ</th>	
            <th>ราคาปก</th>
            <th>Price</th>
            <th>QTY</th>
            <th>รวม</th>
            <th>Total</th>
            <th>ส่วนลด</th>
            <th>วิธีส่ง</th>
        </tr>
    </thead>
    <?php foreach ($dataProvider->getModels() as $model): ?>
        <tr>
            <td>
                <?= $model->purchase_no; ?><br/>
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <span style="color:#ffffff;">.</span><br/>
                <?php endfor; ?>
            </td>
            <td>
                <strong><?= $model->invoice_firstname; ?> <?= $model->invoice_lastname; ?></strong><br/>
                <?= ($model->invoice_idcard ? Html::tag('div', Html::encode('(' . $model->invoice_idcard . ')') . '<br/>') : ''); ?>
                <?= ($model->invoice_company ? Html::tag('div', Html::encode($model->invoice_company) . '<br/>' . ($model->invoice_tax ? Html::encode('(' . $model->invoice_tax) . ')<br/>' : '')) : ''); ?>
                <?= $model->invoice_address; ?>
                <?php if ($model->invoice_tambon): ?>
                    ตำบล/แขวง <?= Html::encode($model->invoice_tambon); ?>
                <?php endif; ?>
                <?php if ($model->invoice_amphur): ?>
                    อำเภอ/เขต <?= Html::encode($model->invoice_amphur); ?>
                <?php endif; ?><br/>
                <?= $model->invoice_province; ?>, <?= $model->invoice_postcode; ?><br/>
                โทร: <?= $model->invoice_phone; ?>
            </td>
            <td>
                <strong><?= $model->delivery_firstname; ?> <?= $model->delivery_lastname; ?></strong><br/>
                <?= ($model->delivery_company ? Html::tag('div', Html::encode($model->delivery_company) . '<br/>') : ''); ?>
                <?= $model->delivery_address; ?>
                <?php if ($model->delivery_tambon): ?>
                    ตำบล/แขวง <?= Html::encode($model->delivery_tambon); ?>
                <?php endif; ?>
                <?php if ($model->delivery_amphur): ?>
                    อำเภอ/เขต <?= Html::encode($model->delivery_amphur); ?>
                <?php endif; ?><br/>
                <?= $model->delivery_province; ?>, <?= $model->delivery_postcode; ?><br/>
                โทร: <?= $model->delivery_phone; ?>
            </td>
            <td>
                <?php foreach ($model->purchaseProducts as $rIndex => $item): ?>
                    <?= $item->product->name; ?><br/>
                <?php endforeach; ?>
                <?php if ($rIndex <= 2): ?>
                    <?php for ($i = $rIndex; $i < 3; $i++): ?>
                        <span style="color:#ffffff;">.</span><br/>
                    <?php endfor; ?>
                <?php endif ?>
            </td>
            <td>
                <?php foreach ($model->purchaseProducts as $rIndex => $item): ?>
                    <?= Yii::$app->formatter->asDecimal($item->product->price, 2); ?><br/>
                <?php endforeach; ?>
                <?php if ($rIndex <= 2): ?>
                    <?php for ($i = $rIndex; $i < 3; $i++): ?>
                        <span style="color:#ffffff;">.</span><br/>
                    <?php endfor; ?>
                <?php endif ?>
            </td>
            <td>
                <?php foreach ($model->purchaseProducts as $rIndex => $item): ?>
                    <?= Yii::$app->formatter->asDecimal($item->price_total, 2); ?><br/>
                <?php endforeach; ?>
                <?php if ($rIndex <= 2): ?>
                    <?php for ($i = $rIndex; $i < 3; $i++): ?>
                        <span style="color:#ffffff;">.</span><br/>
                    <?php endfor; ?>
                <?php endif ?>
            </td>
            <td>
                <?php foreach ($model->purchaseProducts as $rIndex => $item): ?>
                    <?= $item->amount; ?><br/>
                <?php endforeach; ?>
                <?php if ($rIndex <= 2): ?>
                    <?php for ($i = $rIndex; $i < 3; $i++): ?>
                        <span style="color:#ffffff;">.</span><br/>
                    <?php endfor; ?>
                <?php endif ?>
            </td>
            <td>
                <?= Yii::$app->formatter->asDecimal($model->price_total, 2); ?><br/>
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <span style="color:#ffffff;">.</span><br/>
                <?php endfor; ?>
            </td>
            <td>
                <?= Yii::$app->formatter->asDecimal($model->price_grand, 2); ?><br/>
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <span style="color:#ffffff;">.</span><br/>
                <?php endfor; ?>
            </td>
            <td>
                <?= Html::encode($model->order_note); ?>
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <span style="color:#ffffff;">.</span><br/>
                <?php endfor; ?>
            </td>
            <td>
                <?= $model->getDeliveryText(); ?><br/>
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <span style="color:#ffffff;">.</span><br/>
                <?php endfor; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>