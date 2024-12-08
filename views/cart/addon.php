<?php

use app\components\Html;
use yii\helpers\ArrayHelper;
?>
<div class="modal-header">
    <h4 class="modal-title">กำหนดตัวเลือก : <?= Html::encode(ArrayHelper::getValue($model, 'name')); ?></h4>
</div>
<div class="modal-body">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>ตัวเลือก</th>
                <th class="text-right">ราคา</th>
                <th class="text-right">รวม</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->productAddons as $item): ?>
                <tr>
                    <td><?= Html::encode($item->name); ?></td>
                    <td class="text-right text-success">+<?= Yii::$app->formatter->asDecimal($item->price, 2); ?> ฿</td>
                    <td class="text-right text-bold"><?= Yii::$app->formatter->asDecimal($item->priceSell, 2); ?> ฿</td>
                    <td class="text-center">
                        <?= Html::a('เลือก', ['cart/add', 'id' => $model->id, 'addon_id' => $item->id], ['class' => 'btn btn-sm btn-primary btn-add-cart']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', 'javascript:void(0);', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']); ?>
</div>