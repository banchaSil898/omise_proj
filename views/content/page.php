<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use yii\helpers\HtmlPurifier;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => $model->name,
            'url' => ['content/page', 'p' => $model->url_key],
        ]
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => $model->name,
    ])
    ?>
    <?= HtmlPurifier::process($model->description); ?>
    <?php Page::end(); ?>
</div>