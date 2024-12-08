<?php

use app\modules\admin\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
?>
<?php
$this->beginContent('@module/views/product/layouts/form.php', [
    'model' => $model,
]);
?>
<h4>ตั้งค่าราคา</h4>
<?php
$form = ActiveForm::begin([
            'type' => 'horizontal',
        ])
?>
<?=
$form->field($model, 'price', [
    'addon' => [
        'append' => [
            'content' => '฿',
        ],
    ],
])->textInput();
?>
<?=
$form->field($model, 'price_sell', [
    'addon' => [
        'append' => [
            'content' => '฿',
        ],
    ],
])->textInput();
?> 
<?php ActiveForm::end(); ?>
<hr/>
<h4>ตั้งค่า Add-On (กรณีที่ต้องการเพิ่มตัวเลือกให้สินค้าพร้อมบวกราคา)</h4>
<div style="margin:10px;">
    <div class="text-right">
        <?= Html::a(Html::awesome('plus') . ' เพิ่มตัวเลือก', ['product-addon/create', 'id' => $model->id], ['class' => 'btn btn-primary', 'data-modal' => '1']); ?>
    </div>
    <?=
    GridView::widget([
        'responsive' => false,
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => kartik\grid\SerialColumn::class,
            ],
            [
                'attribute' => 'name',
            ],
            [
                'attribute' => 'price',
                'format' => ['decimal', 2]
            ],
            [
                'header' => 'แก้ไข',
                'class' => \kartik\grid\ActionColumn::class,
                'template' => '{product-addon/update}',
                'buttons' => [
                    'product-addon/update' => function($url, $m) {
                        return Html::a(Html::awesome('edit'), $url, ['data-modal' => 1, 'data-pjax' => 0]);
                    },
                ],
            ],
            [
                'header' => 'ลบ',
                'class' => \kartik\grid\ActionColumn::class,
                'template' => '{product-addon/delete}',
                'buttons' => [
                    'product-addon/delete' => function($url, $m) {
                        return Html::a(Html::awesome('trash'), $url, ['data-ajax' => 1, 'data-ajax-confirm' => 'ต้องการลบรายการนี้?', 'data-ajax-method' => 'post']);
                    },
                ],
            ],
        ],
    ]);
    ?>
</div>
<?php $this->endContent(); ?>