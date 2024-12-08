<?php

use codesk\components\Html;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\widgets\Pjax;
?>
<?php $this->beginContent('@app/views/my/layout.php'); ?>
<div>
    <?= Html::a(Html::awesome('plus') . ' เพิ่มที่อยู่', ['address-create', 'id' => $user->id], ['class' => 'btn btn-primary', 'data-modal' => '1', 'data-modal-size' => 'lg']); ?>
</div>
<?php
Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => [
        'class' => 'table table-white table-sm',
    ],
    'columns' => [
        [
            'class' => SerialColumn::className(),
        ],
        [
            'attribute' => 'address',
            'value' => function($model) {
                return $model->getAddress();
            },
        ],
        [
            'attribute' => 'isBillingDefault',
            'headerOptions' => [
                'width' => 140,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
            'value' => function($model) {
                return Html::a(Html::icon($model->isBillingDefault ? 'star' : 'star-empty'), ['default-billing', 'id' => $model->address_id], [
                            'class' => 'text-success',
                            'data-pjax' => '0',
                            'data-ajax' => '1',
                            'data-ajax-method' => 'post',
                            'data-ajax-pjax-reload' => '#pjax-page',]);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'isShippingDefault',
            'headerOptions' => [
                'width' => 140,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
            'value' => function($model) {
                return Html::a(Html::icon($model->isShippingDefault ? 'star' : 'star-empty'), ['default-shipping', 'id' => $model->address_id], [
                            'class' => 'text-success',
                            'data-pjax' => '0',
                            'data-ajax' => '1',
                            'data-ajax-method' => 'post',
                            'data-ajax-pjax-reload' => '#pjax-page',]);
            },
            'format' => 'raw',
        ],
        [
            'header' => 'แก้ไข',
            'class' => ActionColumn::className(),
            'template' => '{update}',
            'buttons' => [
                'update' => function($url, $model) {
                    return Html::a(Html::icon('pencil'), ['address-update', 'address_id' => $model->address_id], ['data-modal' => '1', 'data-pjax' => '0', 'data-modal-size' => 'lg']);
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
                    return Html::a(Html::icon('trash'), ['address-delete', 'address_id' => $model->address_id], [
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
