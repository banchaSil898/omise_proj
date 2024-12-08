<?php

use codesk\components\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;

$cart = $this->context->getCart();
$user = Yii::$app->user;
?>
<header class="header">
    <?php
    NavBar::begin([
        'options' => [
            'class' => 'navbar navbar-top-bar navbar-static-top',
        ],
    ]);
    ?>
    <?=
    Nav::widget([
        'encodeLabels' => false,
        'options' => [
            'class' => 'navbar-nav navbar-left'
        ],
        'items' => $this->context->getMenuTop(),
    ]);
    ?>
    <?=
    Nav::widget([
        'options' => [
            'class' => 'navbar-nav navbar-right'
        ],
        'items' => [
            [
                'label' => 'แจ้งโอนเงิน',
                'url' => ['/transfer/index'],
                'visible' => false,
            ],
            [
                'label' => 'รายชื่อหนังสือถูกใจ',
                'url' => ['/my/wishlist'],
                'visible' => !$user->isGuest,
            ],
            [
                'label' => 'เข้าสู่ระบบ',
                'url' => ['/site/login'],
                'visible' => $user->isGuest,
            ],
            [
                'encode' => false,
                'label' => Html::awesome('user') . ' ' . ArrayHelper::getValue($user->identity, 'name'),
                'url' => ['#'],
                'visible' => !$user->isGuest,
                'items' => [
                    [
                        'label' => 'ข้อมูลบัญชี',
                        'url' => ['/my/index'],
                    ],
                    [
                        'label' => 'รายการสั่งซื้อ',
                        'url' => ['/my/order'],
                    ],
                    [
                        'label' => 'จัดการที่อยู่',
                        'url' => ['/my/address'],
                    ],
                    [
                        'label' => 'เปลี่ยนรหัสผ่าน',
                        'url' => ['/my/change-password'],
                    ],
                    '<li class="divider"><li>',
                    [
                        'label' => 'ออกจากระบบ',
                        'url' => ['/site/logout'],
                    ],
                ],
            ],
            [
                'label' => 'รวม : ' . Yii::$app->formatter->asDecimal($cart->total, 2) . '฿',
                'url' => '#my-cart',
                'linkOptions' => [
                    'data-toggle' => 'collapse',
                ],
                'options' => [
                    'class' => 'active',
                ],
            ],
        ],
    ]);
    ?>
    <?php NavBar::end(); ?>

    <!-- ============================================================= NAVBAR TOPBAR : END ============================================================= -->    <div class="yamm navbar navbar-default navbar-default-book animate-dropdown" role="navigation">
        <div class="container">
            <!-- ============================================================= NAVBAR PRIMARY ============================================================= -->

            <div id="main-nav" class="header-mast">
                <div class="navbar-header">



                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#KYbook-navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    
                    <button id="mobile-cart" type="button" class="navbar-toggle visible-xs">
                        <?= Html::icon('shopping-cart'); ?>
                        <span class="badge badge-cart-items-count"><?= Yii::$app->formatter->asInteger($cart->itemsCount); ?></span>
                    </button>
                    <?= Html::a(Html::img('@web/images/web/micbook-logo-tp.png'), ['site/index'], ['class' => 'navbar-brand']); ?>

                </div><!-- /.navbar-header -->
                <?= $this->render('/widgets/navbar'); ?>

                <a href="#my-cart" class="navbar-btn btn btn-cart" data-toggle="collapse">
                    <?= Html::img('@web/images/shopping-cart.png'); ?>
                    <span class="badge-cart-items-count"><?= Yii::$app->formatter->asInteger($cart->itemsCount); ?></span>
                </a>
            </div><!-- /.header-mast -->
            <!-- ============================================================= NAVBAR PRIMARY : END ============================================================= -->           
        </div><!-- /.container -->
    </div><!-- /.yamm -->

</header><!-- /.header -->