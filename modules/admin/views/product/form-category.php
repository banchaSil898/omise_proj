<?php

use app\models\Folder;
use app\modules\admin\assets\JqueryTreeAsset;
use codesk\components\Html;
use kartik\form\ActiveForm;

JqueryTreeAsset::register($this);
$categories = Folder::find()->andWhere(['level' => 0])->orderBy(['position' => SORT_ASC])->all();
?>
<?php
$this->beginContent('@module/views/product/layouts/form.php', [
    'model' => $model,
]);
?>
<?php
$form = ActiveForm::begin([
            'type' => 'horizontal',
        ])
?>
<h4>เลือกหมวดหมู่</h4>
<ul id="category-tree">
    <li>
        <input type="checkbox"/> (เลือกหมวดหมู่ทั้งหมด)
        <ul>
            <?php foreach ($categories as $category): ?>
                <li>
                    <?= Html::checkbox('items[]', isset($categoryItems[$category->id]) ? true : false, ['value' => $category->id]); ?>
                    <?= Html::encode($category->name); ?>
                    <?php if ($category->categoryItems): ?>
                        <ul>
                            <?php foreach ($category->categoryItems as $item): ?>
                                <li>
                                    <?= Html::checkbox('items[]', isset($categoryItems[$item->id]) ? true : false, ['value' => $item->id]); ?>
                                    <?= Html::encode($item->name); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </li>
</ul>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
<?php
$this->registerJs(<<<JS
        $("#category-tree").tree();
JS
);
?>