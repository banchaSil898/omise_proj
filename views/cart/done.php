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
            'label' => 'ตระกร้าสินค้า',
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
