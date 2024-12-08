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