<?php

use kartik\grid\GridView;

?>
<?=

GridView::widget([
    'responsiveWrap' => false,
    'tableOptions' => [
        'border' => 1,
    ],
    'dataProvider' => $dataProvider,
    'showFooter' => true,
    'columns' => [
        [
            'attribute' => 'product.sku',
            'headerOptions' => [
                'class' => 'text-center',
                'style' => [
                    'width' => '180px',
                ],
            ],
            'options' => [
                'style' => [
                    'mso-number-format' => '"\@"',
                ],
            ],
        ],
        [
            'attribute' => 'product.name',
            'footerOptions' => [
                'class' => 'text-right text-bold',
            ],
            'footer' => 'รวม',
        ],
        [
            'attribute' => 'rec_amount',
            'format' => ['decimal', 2],
            'headerOptions' => [
                'class' => 'text-center',
                'style' => [
                    'width' => '120px',
                ],
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
            'footerOptions' => [
                'class' => 'text-right',
            ],
            'footer' => Yii::$app->formatter->asDecimal($summary->rec_amount, 2),
        ],
        [
            'attribute' => 'rec_count',
            'format' => 'integer',
            'headerOptions' => [
                'class' => 'text-center',
                'style' => [
                    'width' => '120px',
                ],
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
            'footerOptions' => [
                'class' => 'text-right',
            ],
            'footer' => Yii::$app->formatter->asInteger($summary->rec_count),
        ],
    ],
]);
?>