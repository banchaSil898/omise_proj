<?php

use codesk\components\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
?>
<?php $this->beginContent('@app/views/my/layout.php'); ?>
<?php
Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<div class="row">
    <div class="col-sm-offset-2 col-sm-8">
        <?=
        DetailView::widget([
            'options' => [
                'class' => 'detail-view dv-default',
            ],
            'model' => $model,
            'attributes' => [
                'username',
                'firstname',
                'lastname',
                'birth_date:date',
            ],
        ]);
        ?>
        <div class="text-right">
            <?= Html::a(Html::awesome('edit') . ' แก้ไข', ['update'], ['class' => 'btn btn-default', 'data-modal' => '1', 'data-pjax' => '0']); ?>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
<?php $this->endContent(); ?>
