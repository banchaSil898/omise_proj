<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'ร่วมงานกับเรา',
            'url' => ['site/career'],
        ]
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => 'ร่วมงานกับเรา',
    ])
    ?>
    <?php Page::end(); ?>
</div>