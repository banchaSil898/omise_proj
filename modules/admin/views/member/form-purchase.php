<?php

use app\models\Purchase;
use app\modules\admin\components\Html;
use kartik\grid\GridView;
use yii\grid\SerialColumn;
use yii\widgets\Pjax;
?>
<?php

$this->beginContent('@module/views/member/layouts/form.php', [
    'model' => $model,
]);
?>
<?php

Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function($model) {
        $ret = [];
        if ($model->status == Purchase::STATUS_CANCELLED) {
            $ret['class'] = 'text-strike';
        }
        return $ret;
    },
    'columns' => [
        [
            'class' => SerialColumn::className(),
        ],
        [
            'attribute' => 'purchase_no',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
            'value' => function($model) {
                return Html::a(Html::encode($model->purchase_no), ['purchase/update', 'id' => $model->id], ['data-pjax' => '0']);
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'status',
            'value' => function ($model) {
                return $model->getHtmlStatus();
            },
            'format' => 'html',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center text-bold',
            ],
        ],
        [
            'attribute' => 'status_comment',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'price_grand',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
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
    ],
]);
?>
<?php Pjax::end(); ?>
<?php $this->endContent(); ?>
