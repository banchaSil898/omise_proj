<?php

use app\widgets\Page;
use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;

/* @var $form ActiveForm */
?>
<?php
Page::begin([
    'title' => 'รายการสินค้า',
    'subtitle' => 'โปรดตรวจสอบรายการสินค้าของคุณ',
]);
?>
<div id="cart-checkout-table" data-url="<?= Url::to(['api/cart-summary']) ?>">

</div>
<?php Page::end(); ?>