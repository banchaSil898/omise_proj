<?php

use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yiister\gentelella\widgets\Menu;

$bundle = AppAsset::register($this);
$user = Yii::$app->user->identity;
?>
<?php
$this->beginContent('@module/views/layouts/html.php', [
]);
?>
<div class="container body">
    <div class="main_container">

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <?= Html::a(Html::img($bundle->baseUrl . '/images/micbook-logo-inv.png', ['width' => '95%']), ['site/index'], ['class' => 'site_title']); ?>
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <?= Html::img($bundle->baseUrl . '/images/user.png', ['class' => 'img-circle profile_img']); ?>
                    </div>
                    <div class="profile_info">
                        <span>ยินดีต้อนรับ,</span>
                        <h2>ผู้ดูแลระบบ</h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>เมนูหลัก</h3>
                        <?=
                        Menu::widget([
                            'encodeLabels' => false,
                            'items' => [
                                [
                                    'label' => 'หน้าหลัก',
                                    'url' => ['site/index'],
                                    'icon' => 'home',
                                ],
                                [
                                    'label' => 'จัดการหมวดหมู่' . Html::tag('span', Yii::$app->formatter->asInteger(ArrayHelper::getValue($this, 'context.module.stat.countCategory')), ['class' => 'label label-default pull-right']),
                                    'url' => ['product-category/index'],
                                    'icon' => 'sitemap',
                                    'visible' => $user->can('product-category'),
                                ],
                                [
                                    'label' => 'จัดการสินค้า' . Html::tag('span', Yii::$app->formatter->asInteger(ArrayHelper::getValue($this, 'context.module.stat.countProduct')), ['class' => 'label label-default pull-right']),
                                    'url' => ['product/index'],
                                    'icon' => 'cubes',
                                    'visible' => $user->can('product'),
                                ],
                                [
                                    'label' => 'ข้อมูลสำนักพิมพ์' . Html::tag('span', Yii::$app->formatter->asInteger(ArrayHelper::getValue($this, 'context.module.stat.countPublisher')), ['class' => 'label label-default pull-right']),
                                    'url' => ['publisher/index'],
                                    'icon' => 'database',
                                    'visible' => $user->can('publisher'),
                                ],
                                [
                                    'label' => 'รายการสั่งซื้อ' . Html::tag('span', Yii::$app->formatter->asInteger(ArrayHelper::getValue($this, 'context.module.stat.countPurchases')), ['class' => 'label label-' . (ArrayHelper::getValue($this, 'context.module.stat.countPurchases') > 0 ? 'info' : 'default') . ' pull-right']),
                                    'url' => ['purchase/index', 'Purchase' => ['status' => '-1']],
                                    'icon' => 'shopping-cart',
                                    'visible' => $user->can('purchase'),
                                ],
                                [
                                    'label' => 'จัดการโปรโมชั่น',
                                    'url' => '#',
                                    'icon' => 'star',
                                    'visible' => $user->can('promotion'),
                                    'items' => [
                                        [
                                            'label' => 'รายการโปรโมชั่น' . Html::tag('span', Yii::$app->formatter->asInteger(ArrayHelper::getValue($this, 'context.module.stat.countPromotion')), ['class' => 'label label-' . (ArrayHelper::getValue($this, 'context.module.stat.countPromotion') > 0 ? 'info' : 'default') . ' pull-right']),
                                            'url' => ['promotion/index'],
                                        ],
                                        [
                                            'label' => 'ของที่ระลึก',
                                            'url' => ['gift/index'],
                                        ],
                                    ],
                                ],
                                [
                                    'label' => 'ข้อมูลสมาชิก',
                                    'url' => ['member/index'],
                                    'icon' => 'users',
                                    'visible' => $user->can('member'),
                                ],
                                [
                                    'label' => 'จัดการเนื้อหาเว็บไซต์',
                                    'url' => '#',
                                    'icon' => 'globe',
                                    'visible' => $user->can('content'),
                                    'items' => [
                                        [
                                            'label' => 'ตั้งค่าเว็บไซต์',
                                            'url' => ['web-config/index'],
                                        ],
                                        [
                                            'label' => 'ตั้งค่าหน้า Intro',
                                            'url' => ['web-intro/index'],
                                        ],
                                        [
                                            'label' => 'การแปลงคำค้น',
                                            'url' => ['word-convert/index'],
                                        ],
                                        [
                                            'label' => 'ภาพสไลด์หน้าแรก',
                                            'url' => ['slide/index'],
                                        ],
                                        [
                                            'label' => 'หน้าเว็บเพจ',
                                            'url' => ['article/index'],
                                        ],
                                        [
                                            'label' => 'ข่าวประชาสัมพันธ์',
                                            'url' => ['news/index'],
                                        ],
                                    ],
                                ],
                                [
                                    'label' => 'คำถามที่พบบ่อย',
                                    'url' => ['faq/index'],
                                    'icon' => 'question',
                                    'visible' => $user->can('faq'),
                                ],
                                [
                                    'label' => 'ข้อความสอบถาม' . Html::tag('span', Yii::$app->formatter->asInteger(ArrayHelper::getValue($this, 'context.module.stat.countContact')), ['class' => 'label label-' . (ArrayHelper::getValue($this, 'context.module.stat.countContact') > 0 ? 'info' : 'default') . ' pull-right']),
                                    'url' => ['contact/index'],
                                    'icon' => 'comments',
                                    'visible' => $user->can('contact'),
                                ],
                                [
                                    'label' => 'ตั้งค่าการจัดส่งสินค้า',
                                    'url' => ['delivery/index'],
                                    'icon' => 'truck',
                                    'visible' => $user->can('delivery'),
                                ],
                                [
                                    'label' => 'ตั้งค่าระบบอีเมล์',
                                    'url' => ['mail/index'],
                                    'icon' => 'envelope',
                                    'visible' => $user->can('mail'),
                                ],
                                [
                                    'label' => 'บัญชีผู้ใช้งาน',
                                    'url' => ['account/index'],
                                    'icon' => 'user',
                                    'visible' => $user->can('account'),
                                ],
                                [
                                    'label' => 'รายงาน',
                                    'icon' => 'file',
                                    'url' => '#',
                                    'visible' => $user->can('report'),
                                    'items' => [
                                        [
                                            'label' => 'สรุปรายงานคำสั่งซื้อ',
                                            'url' => ['report-purchase/index'],
                                        ],
                                        [
                                            'label' => 'สรุปยอดสั่งซื้อวันนี้',
                                            'url' => ['report-sell/today'],
                                        ],
                                        [
                                            'label' => 'สรุปยอดสั่งซื้อรายวัน',
                                            'url' => ['report-sell/daily'],
                                        ],
                                        [
                                            'label' => 'สรุปยอดสั่งซื้อรายสัปดาห์',
                                            'url' => ['report-sell/weekly'],
                                        ],
                                        [
                                            'label' => 'สรุปยอดสั่งซื้อรายเดือน',
                                            'url' => ['report-sell/monthly'],
                                        ],
                                        [
                                            'label' => 'สรุปยอดสั่งซื้อรายปี',
                                            'url' => ['report-sell/yearly'],
                                        ]
                                    ],
                                ]
                            ],
                        ]);
                        ?>
                    </div>
                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <?= Html::a(Html::icon('off'), ['site/logout'], ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'ออกจากระบบ']); ?>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="https://placehold.it/128x128" alt=""> <?= $user->username; ?>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><?= Html::a(Html::awesome('sign-out') . ' ออกจากระบบ', ['site/logout']); ?></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <?php if (isset($this->params['h1'])): ?>
                <div class="page-title">
                    <div class="title_left">
                        <h1><?= $this->params['h1'] ?></h1>
                    </div>
                    <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">Go!</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>

            <?= $content ?>
        </div>
        <!-- /page content -->
        <!-- footer content -->
        <footer>
            <div class="pull-right">
                ระบบจัดการร้านค้า v2.0<br/>
                สำนักพิมพ์มติชน
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>

</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <?php $flash = Yii::$app->session->getFlash('success'); ?>
    <?php
    Modal::begin([
        'id' => 'alert-modal',
        'header' => Html::tag('h4', 'ข้อความแจ้งเตือน', ['class' => 'modal-title']),
        'headerOptions' => [
            'class' => 'bg-primary',
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

<!-- /footer content -->
<?php $this->endContent(); ?>