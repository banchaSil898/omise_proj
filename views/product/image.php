<?php

use codesk\components\Html;
?>
<div class="modal-body">
    <div class="text-center">
<?= Html::img($model->imageUrl); ?>
    </div>
</div>
<div class="modal-footer">
<?= Html::a('ปิด', '#', ['data-dismiss' => 'modal', 'class' => 'btn btn-default']); ?>
</div>