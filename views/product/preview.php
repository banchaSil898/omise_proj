<?php

use codesk\components\Html;
?>
<div class="modal-header">
    <h3 class="modal-title">ทดลองอ่าน : <small><?= Html::encode($model->name); ?></small></h3>
</div>
<div class="modal-body">
    <?= Html::tag('iframe', '', ['class' => 'pdf-box', 'src' => $model->ebookUrl]); ?>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', ['#'], ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
</div>