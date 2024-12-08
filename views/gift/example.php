<?php

use app\components\Html;
?>
<div class="modal-header">
    <h4 class="modal-title"><?= Html::encode($model->name); ?></h4>
</div>
<div class="modal-body text-center">
    <?= Html::img($model->thumbUrl, ['style' => 'max-width:100%;']); ?>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', '#', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']); ?>
</div>