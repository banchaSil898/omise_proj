<?php

use codesk\components\Html;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;

?>
<?php $this->beginContent('@app/views/my/layout.php'); ?>
<?php Pjax::begin(); ?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    'emptyCell' => '',
    'tableOptions' => [
        'class' => 'table table-white table-xs',
    ],
    'columns' => [
        [
            'attribute' => 'purchase_no',
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 100,
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
            'value' => function($model) {
                return Html::a(Html::encode($model->purchase_no), ['transfer/view', 'order_no' => $model->purchase_no], ['data-pjax' => '0']);
            },
            'format' => 'html',
        ],
        [
            'label' => 'วันที่สั่งซื้อ',
            'attribute' => 'created_at',
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 150,
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
            'format' => 'datetime',
        ],
        [
            'label' => 'สถานะ',
            'attribute' => 'status',
            'value' => function($model) {
                return Html::tag('div', $model->getHtmlStatus(), ['class' => 'text-bold']) . (isset($model->payment_info) ? Html::tag('div', Html::tag('small', $model->payment_info, ['class' => 'text-muted'])) : '');
            },
            'format' => 'html',
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 200,
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'label' => 'หมายเหตุ',
            'attribute' => 'payment_info',
            'value' => function($model) {
                return $model->getHtmlRemark();
            },
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'label' => 'จำนวนเงิน',
            'attribute' => 'price_grand',
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 120,
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
            'format' => ['decimal', 2],
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{transfer}',
            'headerOptions' => [
                'style' => [
                    'min-width' => '100px',
                ],
            ],
            'buttons' => [
                'transfer' => function($url, $model) {
                    return Html::a('แจ้งโอนเงิน', ['order/view', 'order_no' => $model->purchase_no], ['class' => 'btn btn-success btn-sm', 'data-pjax' => '0']);
                },
            ],
            'visibleButtons' => [
                'transfer' => function($model) {
                    return $model->getIsWaitForTransfer();
                },
            ],
        ],
        [
            'header' => 'สั่งซื้ออีกครั้ง',
            'class' => ActionColumn::className(),
            'template' => '{reorder}',
            'headerOptions' => [
                'style' => [
                    'min-width' => '100px',
                ],
            ],
            'buttons' => [
                'reorder' => function($url, $model) {
                    return Html::a(Html::icon('refresh'), $url, ['class' => 'btn btn-info btn-sm', 'data-pjax' => '0', 'data-confirm' => 'ต้องการสั่งซื้อรายการนี้อีกครั้ง ?']);
                },
            ],
        ],
    ],
]);
?>
<?php Pjax::end(); ?>
<?php $this->endContent(); ?>
