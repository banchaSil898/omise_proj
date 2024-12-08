<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use yii\widgets\DetailView;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'ข่าวสาร',
            'url' => ['/content/index'],
        ],
        [
            'label' => $model->name,
            'url' => ['/content/view', 'id' => $model->id],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
    ]);
    ?>

    <?php Page::end(); ?>
</div>