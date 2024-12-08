<?php

use app\components\grid\DeleteButtonColumn;
use app\modules\admin\components\Html;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
?>
<?php
$this->beginContent('@module/views/promotion/layouts/form.php', [
    'model' => $model,
    'showSubmit' => false,
]);
?>
<div>
    <?= Html::a(Html::awesome('tag') . ' เพิ่มคูปอง', ['coupon/create', 'id' => $model->id], ['class' => 'btn btn-primary', 'data-modal' => 1, 'data-modal-size' => 'lg']); ?>
    <?= Html::a(Html::awesome('tags') . ' เพิ่มชุดคูปอง', ['coupon/create-multiple', 'id' => $model->id], ['class' => 'btn btn-primary', 'data-modal' => 1, 'data-modal-size' => 'lg']); ?>

    <?=
    Html::a(Html::awesome('trash') . ' ลบทั้งหมด', ['coupon/delete-all', 'id' => $model->id], [
        'class' => 'btn btn-danger pull-right',
        'data-ajax' => 1,
        'data-ajax-method' => 'post',
        'data-ajax-pjax-reload' => '#pjax-page',
        'data-ajax-confirm' => 'ต้องการลบคูปองทั้งหมดในโปรโมชั่นนี้ ?',
    ]);
    ?>
</div>
<?php
Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function($model) {
        if ($model->is_used) {
            return ['class' => 'text-strike'];
        }
        return [];
    },
    'columns' => [
        [
            'class' => SerialColumn::className(),
        ],
        [
            'class' => kartik\grid\CheckboxColumn::className(),
        ],
        [
            'attribute' => 'code',
        ],
        [
            'attribute' => 'valid_date',
            'format' => 'datetime',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'expire_date',
            'format' => 'datetime',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'label' => 'จำนวนการใช้งาน',
            'attribute' => 'usage_current',
            'value' => function($model) {
                return Yii::$app->formatter->asInteger($model->usage_current) . '/' . Yii::$app->formatter->asInteger($model->usage_max);
            },
            'format' => 'raw',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'member.username',
        ],
        [
            'header' => 'แก้ไข',
            'class' => ActionColumn::className(),
            'template' => '{coupon/update}',
            'buttons' => [
                'coupon/update' => function($url) {
                    return Html::a(Html::icon('pencil'), $url, ['data-modal' => '1', 'data-pjax' => '0']);
                },
            ],
            'headerOptions' => [
                'class' => 'text-center',
                'width' => 50,
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'class' => DeleteButtonColumn::className(),
            'name' => 'coupon/delete',
        ],
    ],
]);
?>
<?php Pjax::end(); ?>
<?php $this->endContent(); ?>