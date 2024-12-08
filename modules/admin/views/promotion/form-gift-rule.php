<?php

use app\components\Html;
use app\models\Promotion;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

?>
<?php

$this->beginContent('@module/views/promotion/layouts/form.php', [
    'model' => $model,
]);
?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => 'รูป',
            'attribute' => 'gift.cover_url',
            'value' => function($model) {
                return Html::img($model->gift->thumbUrl ? $model->gift->thumbUrl : Html::placeholder(48, 48), ['width' => 48]);
            },
            'format' => 'raw',
            'headerOptions' => [
                'width' => 64,
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'style' => 'padding:2px;',
                'class' => 'text-center',
            ],
        ],
        [
            'label' => 'ของที่ระลึก',
            'attribute' => 'gift_id',
            'value' => function($model) {
                return Html::encode(ArrayHelper::getValue($model, 'gift.name'));
            },
            'format' => 'raw',
        ],
        [
            'header' => $model->promotion_type == Promotion::TYPE_GIFT_BY_THING ? 'เงื่อนไขจำนวน (เล่ม)' : 'เงื่อนไขราคา (บาท)',
            'class' => EditableColumn::className(),
            'attribute' => 'buy_rate',
        ],
    ],
]);
?>
<?php $this->endContent(); ?>