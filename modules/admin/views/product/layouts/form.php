<?php

use app\components\Html;
use app\models\Product;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yiister\gentelella\widgets\Panel;

/* @var $model Product */
$dimensions = $model->getValidateDimension();
?>
<?php
Panel::begin([
    'header' => 'ข้อมูลสินค้า' . ($model->isNewRecord ? '' : ' : ' . Html::encode($model->name)),
    'icon' => 'cubes',
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
                    'label' => Html::awesome('file') . ' ข้อมูลทั่วไป' . (ArrayHelper::getValue($dimensions, 'default') ? Html::yes() : Html::no()),
                    'url' => ['update', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('gift') . ' สินค้าในชุดรวม' . Html::tag('span', Yii::$app->formatter->asInteger($model->getProductBundles()->count()), ['class' => 'label label-default pull-right']),
                    'url' => ['update-bundle', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-bundle',
                    'visible' => !$model->isNewRecord && $model->isBundle,
                ],
                [
                    'label' => Html::awesome('dollar') . ' ราคา' . (ArrayHelper::getValue($dimensions, 'update-price') ? Html::yes() : Html::no()),
                    'url' => ['update-price', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-price',
                    'visible' => !($model->isNewRecord || in_array($model->product_type, [Product::TYPE_FOLDER])),
                ],
                [
                    'label' => Html::awesome('info-circle') . ' ข้อมูล Meta',
                    'url' => ['update-meta', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-meta',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('photo') . ' รูปแสดงสินค้า' . (ArrayHelper::getValue($dimensions, 'update-photo') ? Html::yes() : Html::no()),
                    'url' => ['update-photo', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-photo',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('files-o') . ' รูปประกอบอื่นๆ' . Html::tag('span', Yii::$app->formatter->asInteger($model->getProductImages()->count()), ['class' => 'label label-default pull-right']),
                    'url' => ['update-image', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-image',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('file-pdf-o') . ' ทดลองอ่าน',
                    'url' => ['update-ebook', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-ebook',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('sitemap') . ' หมวดหมู่' . Html::tag('span', Yii::$app->formatter->asInteger($model->getProductFolders()->count()), ['class' => 'label label-default pull-right']),
                    'url' => ['update-category', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-category',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('object-group') . ' สินค้าที่เกี่ยวข้อง' . Html::tag('span', Yii::$app->formatter->asInteger($model->getProductRelates()->count()), ['class' => 'label label-default pull-right']),
                    'url' => ['update-relate', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-relate',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('gift') . ' จำนวนสินค้า' . Html::tag('span', Yii::$app->formatter->asInteger($model->stock), ['class' => 'label label-default pull-right']),
                    'url' => ['update-stock', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-stock',
                    'visible' => !$model->isNewRecord,
                ],
                [
                    'label' => Html::awesome('globe') . ' มุมมองหน้าร้าน',
                    'url' => ['update-preview', 'id' => $model->id],
                    'active' => $this->context->action->id === 'update-preview',
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