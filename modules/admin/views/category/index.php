<?php

use codesk\components\Html;
use yiister\gentelella\widgets\Panel;
?>
<?php
Panel::begin([
    'header' => 'จัดการประเภทสินค้า',
]);
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">ประเภทสินค้า</th>
            <th class="text-center">สถานะ</th>
            <th class="text-center">ลำดับ</th>
            <th class="text-center">จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $count => $item): ?>
            <tr>
                <td class="text-center"><?= $count + 1; ?>.</td>
                <td><?= $item; ?></td>
                <td class="text-center text-success">เปิดใช้งาน</td>
                <td class="text-center">
                    <?= Html::a(Html::icon('arrow-up'), ['#']); ?>
                    <?= Html::a(Html::icon('arrow-down'), ['#']); ?>
                </td>
                <td class="text-center">
                    <?= Html::a(Html::icon('pencil'), ['#']); ?>
                    <?= Html::a(Html::icon('trash'), ['#']); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php Panel::end(); ?>