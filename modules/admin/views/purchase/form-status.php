<?php

use app\models\Purchase;
use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
?>
<?php
$this->beginContent('@module/views/purchase/layouts/form.php', [
    'model' => $model,
]);
?>
<div class="well well-sm text-center">
    <span class="btn btn-success">1.สั่งซื้อสินค้า</span> <?= Html::awesome('arrow-right'); ?> 
    <span class="btn btn-success">2.รอชำระเงิน</span> <?= Html::awesome('arrow-right'); ?> 
    <span class="btn btn-<?= $model->isPaid ? 'success' : 'default'; ?>">3.ชำระเงินเรียบร้อย</span> <?= Html::awesome('arrow-right'); ?> 
    <span class="btn btn-<?= $model->isDelivery ? 'success' : 'default'; ?>">4.จัดส่งสินค้า</span>
</div>
<div class="row">
    <div class="col-sm-6">
        <h4 class="title">บันทึกกิจกรรม</h4>
        <?php
        $form = ActiveForm::begin([
                    'type' => 'horizontal',
        ]);
        ?>
        <?= $form->field($state, 'status')->dropDownList($state->getPossibleStatusOptions()); ?>
        <?=
        $form->field($state, 'is_sendmail')->checkbox([
            'label' => 'แจ้งให้ลูกค้าทราบ',
        ]);
        ?>
        <?=
        $form->field($state, 'description')->textarea([
            'rows' => 5,
        ]);
        ?>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-2">
                <?= Html::submitButton('บันทึกกิจกรรม', ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-sm-6">
        <h4 class="title">รายการกิจกรรม</h4>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'status',
                    'value' => function($model) {
                        return Purchase::getStatusOptions($model->status);
                    },
                ],
                [
                    'attribute' => 'description',
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'datetime',
                ],
            ],
        ]);
        ?>
    </div>
</div>

<?php $this->endContent(); ?>
