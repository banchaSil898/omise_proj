<?php

use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use miloschuman\highcharts\Highcharts;
use yiister\gentelella\widgets\grid\GridView;
use yiister\gentelella\widgets\Panel;

$bundle = AppAsset::register($this);
?>
<!-- top tiles -->
<div class="row tile_count">
    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-dollar"></i> รวมยอดขาย (บาท)</span>
        <div class="count green"><?= Yii::$app->formatter->asDecimal($stat['all-sell'], 2); ?></div>
        <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i> <?= Yii::$app->formatter->asDecimal($stat['sell-rate'], 2); ?>% </i> จากเดือนที่ผ่านมา</span>-->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-money"></i> ยอดขายเดือนที่ผ่านมา (บาท)</span>
        <div class="count"><?= Yii::$app->formatter->asDecimal($stat['prev-month-sell'], 2); ?></div>

    </div>
    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-users"></i> จำนวนสมาชิก (ราย)</span>
        <div class="count"><?= Yii::$app->formatter->asInteger($stat['member-all']); ?></div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i> <?= Yii::$app->formatter->asDecimal($stat['member-rate'], 2); ?>% </i> จากเดือนที่ผ่านมา</span>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-shopping-basket"></i>  คำสั่งซื้อรอดำเนินการ </span>
        <div class="count"><?= Yii::$app->formatter->asInteger($stat['purchase-wait']); ?></div>
    </div>
</div>
<!-- /top tiles -->
<div class="row">
    <div class="col-sm-5">
        <?php Panel::begin([]); ?>
        <h4 class="title">รายสินค้าที่เหลือจำนวนน้อย <small><?=
                Html::a('[ดูทั้งหมด]', ['product/index',
                    'Product' => [
                        'search' => [
                            'condition' => 'lowstock',
                        ],
                    ],
                    'sort' => 'stock',
                        ], ['class' => 'pull-right']);
                ?></small></h4>
        <?=
        GridView::widget([
            'options' => [
                'style' => 'margin-bottom:20px;',
            ],
            'dataProvider' => $lowStockDataProvider,
            'layout' => '{items}',
            'columns' => [
                'name',
                [
                    'label' => 'จำนวน',
                    'attribute' => 'stock',
                    'format' => 'integer',
                    'headerOptions' => [
                        'width' => 80,
                        'class' => 'text-right',
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
            ],
        ]);
        ?>
        <h4 class="title">คำสั่งซื้อ 5 คำสั่งซื้อล่าสุด</h4>
        <?=
        GridView::widget([
            'options' => [
                'style' => 'margin-bottom:20px;',
            ],
            'dataProvider' => $topPurchaseDataProvider,
            'layout' => '{items}',
            'columns' => [
                [
                    'label' => 'ลูกค้า',
                    'attribute' => 'buyerFullName',
                ],
                [
                    'label' => 'รายการ',
                    'value' => function($model) {
                        return $model->getPurchaseProducts()->count();
                    },
                    'format' => 'integer',
                    'headerOptions' => [
                        'width' => 80,
                        'class' => 'text-right',
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
                [
                    'label' => 'จำนวนเงิน',
                    'attribute' => 'price_grand',
                    'format' => ['decimal', 2],
                    'headerOptions' => [
                        'width' => 80,
                        'class' => 'text-right',
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
            ],
        ]);
        ?>
        <h4 class="title">คำค้นหา 5 รายการล่าสุด</h4>
        <?=
        GridView::widget([
            'options' => [
                'style' => 'margin-bottom:20px;',
            ],
            'dataProvider' => $lastSearchDataProvider,
            'layout' => '{items}',
            'columns' => [
                [
                    'attribute' => 'keyword',
                ],
                [
                    'attribute' => 'result_count',
                    'format' => 'integer',
                    'headerOptions' => [
                        'class' => 'text-right',
                        'width' => 80,
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
                [
                    'attribute' => 'result_time',
                    'format' => 'integer',
                    'headerOptions' => [
                        'class' => 'text-right',
                        'width' => 80,
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
            ],
        ]);
        ?>
        <h4 class="title">คำค้นหา 5 อันดับสูงสุด</h4>
        <?=
        GridView::widget([
            'options' => [
                'style' => 'margin-bottom:20px;',
            ],
            'dataProvider' => $topSearchDataProvider,
            'layout' => '{items}',
            'columns' => [
                [
                    'attribute' => 'keyword',
                ],
                [
                    'attribute' => 'result_count',
                    'format' => 'integer',
                    'headerOptions' => [
                        'class' => 'text-right',
                        'width' => 80,
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
                [
                    'attribute' => 'result_time',
                    'format' => 'integer',
                    'headerOptions' => [
                        'class' => 'text-right',
                        'width' => 80,
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
            ],
        ]);
        ?>
        <?php Panel::end(); ?>
    </div> 
    <div class="col-sm-7">
        <?php Panel::begin(); ?>
        <?=
        Highcharts::widget([
            'options' => $areaChartOptions,
        ]);
        ?>
        <?php Panel::end(); ?>
        <?php Panel::begin(); ?>
        <h4 class="title">สมาชิก 5 รายล่าสุด</h4>
        <?=
        GridView::widget([
            'options' => [
                'style' => 'margin-bottom:20px;',
            ],
            'dataProvider' => $lastMemberDataProvider,
            'layout' => '{items}',
            'columns' => [
                [
                    'attribute' => 'fullname',
                    'value' => function($model) {
                        return Html::a($model->fullname, ['member/update', 'id' => $model->id]);
                    },
                    'format' => 'html',
                ],
                [
                    'attribute' => 'username',
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'datetime'
                ],
            ],
        ]);
        ?>
        <?php Panel::end(); ?>
    </div> 
</div>
