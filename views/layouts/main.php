<?php

use kartik\social\GoogleAnalytics;
use richardfan\widget\JSRegister;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
?>
<?php $this->beginContent('@app/views/layouts/html.php'); ?>
<?= GoogleAnalytics::widget(); ?>
<div class="wrapper">
    <?= $this->render('/widgets/cart'); ?>
    <?= $this->render('/widgets/header'); ?>
    <div class="content">
        <?= $content; ?>
        <a class="scrollup hidden-xs hidden-sm" href="#" style="display: none;">
            <?= Html::img('@web/images/top-scroll.png'); ?>
        </a>
    </div>
    <?= $this->render('/widgets/footer'); ?>
</div>
<?= $this->render('//widgets/pdpa');?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <?php $flash = Yii::$app->session->getFlash('success'); ?>
    <?php
    Modal::begin([
        'id' => 'alert-modal',
        'header' => Html::tag('h4', 'ข้อความแจ้งเตือน', ['class' => 'modal-title text-bold']),
        'headerOptions' => [
            'class' => 'bg-success',
        ],
        'clientOptions' => [
            'show' => true,
        ],
    ]);
    ?>
    <?= ArrayHelper::getValue($flash, 'body'); ?>
    <?php Modal::end(); ?>
<?php endif; ?>
<?php
Modal::begin([
    'id' => 'base-modal',
]);
?>
<?php Modal::end(); ?>
<?php JSRegister::begin(); ?>
<script>

    $(document).on("click", "#btn-cart-close", function () {
        $('#my-cart').collapse('hide');
        return false;
    });

    $(document).on("click", "#mobile-cart", function () {
        $('#my-cart').collapse('toggle');
        return false;
    });
/*
    $(window).scroll(function (event) {
        var scroll = $(window).scrollTop();
        if (scroll > 50) {
            $('.btn-cart').css('position', 'fixed');
        } else {
            $('.btn-cart').css('position', 'absolute');
        }
    });*/
</script>
<?php JsRegister::end(); ?>
<?php $this->endContent(); ?>
