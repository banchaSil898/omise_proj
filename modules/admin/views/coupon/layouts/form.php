<?php

use app\components\Html;
use app\models\Product;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use yiister\gentelella\widgets\Panel;
?>
<?php
Panel::begin([
    'header' => 'ข้อมูลคูปอง' . ($model->isNewRecord ? '' : ' : ' . Html::encode($model->code)),
    'icon' => 'ticket',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('arrow-left') . ' ย้อนกลับ',
            'url' => Url::previous('product') ? Url::previous('product') : ['index'],
            'linkOptions' => [
                'class' => 'btn btn-default',
            ],
        ],
        [
            'encode' => false,
            'label' => Html::icon('floppy-save') . ' บันทึกข้อมูล',
            'linkOptions' => [
                'class' => 'btn btn-save btn-primary',
            ],
        ]
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
                    'label' => Html::awesome('files-o') . ' ประวัติการใช้งาน',
                    'url' => ['update-usage', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update',
                    'visible' => !$model->isNewRecord,
                ],
            ],
        ]);
        ?>
    </div>
    <div id="product-frm" class="col-md-10">
        <?= $content; ?>
    </div>
</div>
<?php Panel::end(); ?>
<?php
$this->registerJs(<<<JS
        $('.btn-save').on("click", function(){
            $('#product-frm form').submit();
            return false;
        });
JS
)?>