<?php

use codesk\components\Html;

$itemOptions = [];
if ($model->background_color) {
    Html::addCssStyle($itemOptions, ['background-color' => $model->background_color]);
}
if ($model->background_url) {
    Html::addCssStyle($itemOptions, [
        'background-image' => 'url(' . $model->backgroundUrl . ')',
        'background-size' => 'cover',
    ]);
}
?>

<div class="item">
    <?= Html::beginTag('div', $itemOptions); ?>
    <div class="container">
        <?= $model->description; ?>
    </div>
    <?= Html::endTag('div'); ?>
</div>
