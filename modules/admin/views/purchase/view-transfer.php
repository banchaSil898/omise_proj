<?php

use app\components\Html;
?>
<div class="modal-header">
    <h4 class="modal-title">หลักฐานการโอน : <span class="text-primary"><?= Html::encode($model->purchase_no); ?></span></h4>
</div>
<div class="modal-body">
    <?php if ($content): ?>
        <div class="text-center"> 
            <?= Html::img($content, ['style' => 'width:100%']); ?>
        </div>
    <?php else: ?>
        <p>ไม่สามารถแสดง Preview สำหรับไฟล์ <?= Html::encode($model->transfer_file); ?> กรุณาใช้วิธีดาวน์โหลด</p>
    <?php endif; ?>
</div>
<div class="modal-footer">
    <?= Html::a(Html::icon('download') . ' ดาวน์โหลด', ['download-transfer', 'id' => $model->id], ['class' => 'pull-left btn btn-primary']); ?>
    <?= Html::a('ปิด', '#', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']); ?>
</div>