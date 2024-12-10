<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
$get = Yii::$app->request->get();
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'ชำระเงินด้วยบัตรเครดิต',
            'url' => ['#'],
        ],
    ],
]);
?>
<div class="container">
    <div class="text-center">
        <?php
        Page::begin([
            'title' => 'QR Code Promptpay',
        ])
        ?>
        <?= var_dump($get) ?>
        <h2 class="text-center">
            <?= Html::img(Yii::$app->request->get('qr_image'), ['alt' => 'My logo', 'style'=> 'max-width:15em;']) ?>
        </h2>
    </div>
    <?php Page::end(); ?>
</div>
<?php
// $this->registerJs(<<<JS
//         window.setTimeout(function(){
//             $("#frm-payment").submit();
//         },3000);
// JS
// );
?>