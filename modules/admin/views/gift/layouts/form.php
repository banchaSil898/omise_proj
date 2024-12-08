<?php

use codesk\components\Html;
use yii\bootstrap\Nav;
use yiister\gentelella\widgets\Panel;
?>
<?php
Panel::begin([
    'header' => 'ของที่ระลึก' . ($model->isNewRecord ? '' : ' : ' . Html::encode($model->name)),
    'icon' => 'gift',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('arrow-left') . ' ย้อนกลับ',
            'url' => ['index'],
            'linkOptions' => [
                'class' => 'btn btn-default',
            ],
        ],
        [
            'encode' => false,
            'label' => Html::icon('floppy-save') . ' บันทึกข้อมูล',
            'linkOptions' => [
                'class' => 'btn btn-save btn-default',
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
                    'label' => Html::awesome('photo') . ' รูปปก',
                    'url' => ['update-photo', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-photo',
                    'visible' => !$model->isNewRecord,
                ],
                /*
                [
                    'label' => Html::awesome('files-o') . ' รูปอื่นๆ',
                    'url' => ['update-image', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-image',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('list') . ' คุณสมบัติ',
                    'url' => ['update-attr', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-attr',
                    'visible' => !$model->isNewRecord,
                ],*/
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