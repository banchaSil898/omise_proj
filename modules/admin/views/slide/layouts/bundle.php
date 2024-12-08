<?php

use app\modules\admin\components\Html;
use yii\bootstrap\Nav;
use yiister\gentelella\widgets\Panel;
?>
<?php
Panel::begin([
    'header' => 'จัดการเนื้อหาเว็บไซต์ <small>ภาพสไลด์หน้าแรก</small>',
    'icon' => 'globe',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('arrow-left') . ' ย้อนกลับ',
            'url' => ['index'],
            'linkOptions' => [
                'class' => 'btn btn-default',
            ],
        ],
    ],
]);
?>
<div class="row">
    <div class="col-md-2">
        <?=
        Nav::widget([
            'options' => [
                'class' => 'nav-product'
            ],
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => Html::awesome('file') . ' ข้อมูลทั่วไป',
                    'url' => ['create'],
                    'active' => $this->context->action->id === 'create',
                    'visible' => $model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('file') . ' ข้อมูลทั่วไป',
                    'url' => ['update', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('cubes') . ' ข้อมูลสินค้า' . Html::tag('span', Yii::$app->formatter->asInteger($model->getSlideProducts()->count()), ['class' => 'label label-default pull-right']),
                    'url' => ['bundle-product', 'id' => $model->id],
                    'active' => $this->context->action->id === 'bundle-product',
                    'visible' => !$model->isNewRecord
                ],
            ],
        ]);
        ?>
    </div>
    <div class="col-sm-10">
        <?= $content; ?>        
    </div>
</div>
<?php Panel::end(); ?>