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
            'label' => 'ตรวจสอบสถานะ',
            'url' => ['#'],
        ],
    ],
]);
?>
<div class="container">
    <?=
    $this->render('/widgets/purchase', [
        'model' => $model,
    ]);
    ?>
</div>