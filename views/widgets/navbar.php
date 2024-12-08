<?php

use app\models\Content;
use app\models\Folder;
use codesk\components\Html;
use yii\helpers\ArrayHelper;

$user = Yii::$app->user;
$categories = Folder::find()->defaultScope()->all();
$menuItems = [];
$items = Content::find()->where([
            'content_type' => Content::TYPE_ARTICLE,
        ])
        ->orderBy([
            'order_no' => SORT_ASC,
        ])
        ->all();
foreach ($items as $item) {
    $menuItems[] = [
        'label' => Html::encode($item->name),
        'url' => ['/content/page', 'p' => Html::encode($item->url_key)],
        'linkOptions' => [
            'data-pjax' => 0,
        ],
    ];
}
?>
<div class="collapse navbar-collapse" id="KYbook-navbar">
    <ul class="nav navbar-nav navbar-right">
        <?php if ($user->isGuest) : ?>
            <li class="<?= $this->context->id === 'site' ? 'active' : ''; ?> visible-xs">
                <?= Html::a(Html::icon('log-in') . ' เข้าสู่ระบบ', ['site/login']); ?>
            </li>
        <?php else: ?>
            <li class="visible-xs"><?= Html::a(Html::icon('user') . ' ' . ArrayHelper::getValue($user->identity, 'name'), ['/my/index']); ?></li>
            <li class="visible-xs"><?= Html::a(Html::icon('list') . ' รายการสั่งซื้อ', ['/my/order']); ?></li>
            <li class="visible-xs"><?= Html::a(Html::icon('heart') . ' รายชื่อหนังสือถูกใจ', ['/my/wishlist']); ?></li>


            <li class="visible-xs"><?= Html::a(Html::icon('map-marker') . ' จัดการที่อยู่', ['/my/address']); ?></li>
            <li class="visible-xs"><?= Html::a(Html::icon('lock') . ' เปลี่ยนรหัสผ่าน', ['/my/change-password']); ?></li>
            <li class="visible-xs"><?= Html::a(Html::icon('log-out') . ' ออกจากระบบ', ['/site/logout']); ?></li>
        <?php endif; ?>
        <li class="divider visible-xs"></li>
        <li class="dropdown yamm-fw <?= $this->context->id === 'category' ? 'active' : ''; ?>">
            <?= Html::a('หมวดหมู่หนังสือ', ['category/index']); ?>
            <ul class="list-unstyled dropdown-menu dropdown-megamenu">
                <li>
                    <div class="yamm-content">
                        <div class="row">
                            <?php foreach ($categories as $category): ?>
                                <div class="col-sm-6">
                                    <ul>
                                        <li class="menu-header" role="presentation">
                                            <strong><?= Html::a(Html::encode($category->name), ['category/index', 'id' => $category->id], ['data-pjax' => '0']); ?></strong>
                                        </li>
                                        <?php foreach ($category->categoryItems as $item): ?>
                                            <li><?= Html::a(Html::encode($item->name), ['category/index', 'id' => $category->id, 'item_id' => $item->id], ['data-pjax' => '0']); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                            <div class="clearfix visible-xs"></div>
                        </div><!-- /.row -->
                    </div><!-- /.yamm-content -->
                </li>
            </ul><!-- /.dropdown-menu -->
        </li>
        <li class="<?= $this->context->id === 'recommended' ? 'active' : ''; ?>">
            <?= Html::a('หนังสือแนะนำ', ['recommended/index']); ?>
        </li>
        <li class="<?= $this->context->id === 'bestseller' ? 'active' : ''; ?>">
            <?= Html::a('หนังสือขายดี', ['bestseller/index']); ?>
        </li> 
        <li class="<?= $this->context->id === 'other-publisher' ? 'active' : ''; ?> hidden-sm">
            <?= Html::a('หนังสือสำนักพิมพ์อื่น', ['other-publisher/index']); ?>
        </li> 
        <li class="<?= $this->context->id === 'promotion' ? 'active' : ''; ?> hidden-sm">
            <?= Html::a('โปรโมชั่น', ['promotion/index']); ?>
        </li> 
        <li class="<?= $this->context->id === 'term' ? 'active' : ''; ?> visible-xs-block">
            <?= Html::a('วิธีการชำระเงิน', ['site/term']); ?>
        </li>
        <?php foreach ($menuItems as $menuItem): ?>
            <li class="<?= $this->context->id === 'page' ? 'active' : ''; ?> visible-xs-block">
                <?= Html::a($menuItem['label'], $menuItem['url']); ?>
            </li>
        <?php endforeach; ?>
        <li class="<?= $this->context->id === 'contact' ? 'active' : ''; ?> visible-xs-block">
            <?= Html::a('ติดต่อเรา', ['site/contact']); ?>
        </li>       
    </ul><!-- /.nav -->
</div><!-- /.collapse -->