<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
?>
<?php
$this->beginContent('@module/views/product/layouts/form.php', [
    'model' => $model,
]);
?>
<div class="row">
    <div class="col-sm-8">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => SerialColumn::className(),
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'datetime',
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                ],
                [
                    'attribute' => 'amount',
                    'value' => function($model) {
                        return ($model->amount > 0 ? '+' : '') . Yii::$app->formatter->asInteger($model->amount);
                    },
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                ],
                [
                    'attribute' => 'amount_new',
                    'format' => 'integer',
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                ],
                [
                    'attribute' => 'base_price',
                    'format' => ['decimal', 2],
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
                [
                    'attribute' => 'description',
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
    </div>
    <div class="col-sm-4">
        <div class="well well-sm">
            <h4 class="text-bold">เพิ่มสินค้า</h4>
            <?php
            $form = ActiveForm::begin([
                        'type' => 'horizontal',
                        'formConfig' => [
                            'labelSpan' => 4
                        ],
            ]);
            ?>
            <?=
            $form->field($stock, 'amount', [
            ])->textInput();
            ?>
            <?=
            $form->field($stock, 'base_price', [
                'addon' => [
                    'append' => [
                        'content' => '฿',
                    ],
                ],
            ])->textInput();
            ?>
            <?=
            $form->field($stock, 'description', [
            ])->textArea([
                'rows' => 4
            ]);
            ?>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <?= Html::submitButton('บันทึกข้อมูล', ['class' => 'btn btn-primary']); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php $this->endContent(); ?>