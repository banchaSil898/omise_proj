<?php

use codesk\components\Html;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\widgets\Pjax;
?>
<?php
$this->beginContent('@module/views/member/layouts/form.php', [
    'model' => $model,
]);
?>
<div>
    <?= Html::a(Html::awesome('plus') . ' เพิ่มที่อยู่', ['member/address-create', 'id' => $model->id], ['class' => 'btn btn-primary', 'data-modal' => '1']); ?>
</div>
<?php
Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => SerialColumn::className(),
        ],
        [
            'attribute' => 'address',
        ],
        [
            'attribute' => 'is_primary',
            'headerOptions' => [
                'width' => 80,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
            'value' => function($model) {
                return $model->is_primary ? Html::a(Html::icon('star'), ['toggle-status', 'member_id' => $model->member_id, 'address_id' => $model->address_id, 'attribute' => 'is_primary'], ['class' => 'text-success']) : Html::a(Html::icon('star-empty'), ['toggle-status', 'member_id' => $model->member_id, 'address_id' => $model->address_id, 'attribute' => 'is_primary'], ['class' => 'text-danger']);
            },
            'format' => 'raw',
        ],
        [
            'header' => 'แก้ไข',
            'class' => ActionColumn::className(),
            'template' => '{update}',
            'buttons' => [
                'update' => function($url, $model) {
                    return Html::a(Html::icon('pencil'), ['address-update', 'member_id' => $model->member_id, 'address_id' => $model->address_id], ['data-modal' => '1', 'data-pjax' => '0']);
                },
            ],
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'header' => 'ลบ',
            'class' => ActionColumn::className(),
            'template' => '{delete}',
            'buttons' => [
                'delete' => function($url, $model) {
                    return Html::a(Html::icon('trash'), ['address-delete', 'member_id' => $model->member_id, 'address_id' => $model->address_id], [
                                'data-pjax' => '0',
                                'data-ajax' => '1',
                                'data-ajax-method' => 'post',
                                'data-ajax-pjax-reload' => '#pjax-page',
                                'data-ajax-confirm' => 'ต้องการลบรายการนี้?',
                    ]);
                },
            ],
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
    ],
]);
?> 
<?php Pjax::end(); ?>
<?php $this->endContent(); ?>
