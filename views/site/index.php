<?php

use app\assets\SlickAsset;
use app\models\Configuration;
use app\models\Slide;
use app\widgets\Breadcrumbs;
use app\widgets\ProductCarousel;
use codesk\components\Html;
use richardfan\widget\JSRegister;
use yii\bootstrap\Modal;
use yii\widgets\ListView;

SlickAsset::register($this);
?>
<div class="home page">
    <div class="container">
        <div id="main-slider">
            <?php foreach ($slides as $slide): ?>
                <?php if ($slide->slide_type === Slide::TYPE_PRODUCT): ?>
                    <?php $p = $slide->product; ?>
                    <?php if (isset($p)): ?>
                        <div class="slick-me-item bg-resp" style="background-image: url(<?= $slide->imageUrl; ?>)">
                            <div class="slick-new-box container">
                                <div class="slick-new-box-inner">
                                    <div class="slick-new-obj">
                                        <div class="row no-gutters flex">
                                            <div class="col-xs-4">
                                                <div class="slick-book">
                                                    <?= Html::a(Html::img($p->cover_url), $p->seoUrl); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-8 slick-desc-pane">
                                                <div class="slick-backdrop">
                                                    <div style="margin-bottom:10px;">
                                                        <span style="color:#ffffff;font-size:1.8vh;"><?= Html::a(Html::encode($p->name), $p->seoUrl, ['style' => 'color:#ffffff;']); ?></span>
                                                    </div>
                                                    <div style="margin-bottom:30px;overflow: hidden;text-overflow:ellipsis;">
                                                        <span style="color:#ececec;font-size:1.3vh;"><?= Html::encode($p->brief); ?></span>
                                                    </div>
                                                    <div class="cart-action">
                                                        <?= Html::a(Html::awesome('shopping-cart') . ' ' . Yii::$app->formatter->asDecimal($p->currentPrice, 2) . '฿', ['cart/add', 'id' => $p->id], ['class' => 'btn btn-lg btn-primary btn-add-cart']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php elseif ($slide->slide_type === Slide::TYPE_HTML): ?>
                    <div class="slick-me-item bg-resp slick-me-fix" style="background-image: url(<?= $slide->imageUrl; ?>)">
                        <div class="slick-new-box container">
                            <div class="slick-new-box-inner">
                                <div class="slick-new-obj">
                                    <?= $slide->html; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($slide->slide_type === Slide::TYPE_BUNDLE): ?>
                    <div class="slick-me-item bg-resp">
                        <?= Html::a(Html::img($slide->imageUrl, ['class' => 'img-resp']), ['highlight/view', 'id' => $slide->id], ['class' => 'slick-new-box', 'target' => '_blank']); ?>
                    </div>
                <?php else: ?>
                    <div class="slick-me-item bg-resp">
                        <?= Html::a(Html::img($slide->imageUrl, ['class' => 'img-resp']), $slide->url, ['class' => 'slick-new-box', 'target' => '_blank']); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?=
    Breadcrumbs::widget([
        'showHome' => false,
    ]);
    ?>
    <div class="container">
        <?php if ($recommendProvider->totalCount): ?>
            <?=
            ProductCarousel::widget([
                'title' => 'หนังสือแนะนำ',
                'subtitle' => Html::a('[ดูทั้งหมด]', ['recommended/index']),
                'dataProvider' => $recommendProvider,
            ]);
            ?>
            <hr/>
        <?php endif; ?>

        <?php if ($newProvider->totalCount): ?>
            <?=
            ProductCarousel::widget([
                'title' => 'หนังสือสำนักพิมพ์มติชน',
                'subtitle' => Html::a('[ดูทั้งหมด]', ['new/index']),
                'dataProvider' => $newProvider,
            ]);
            ?>
            <hr/>
        <?php endif; ?>
        <?php if ($sellProvider->totalCount): ?>
            <?=
            ProductCarousel::widget([
                'title' => 'หนังสือขายดี',
                'subtitle' => Html::a('[ดูทั้งหมด]', ['bestseller/index']),
                'dataProvider' => $sellProvider,
            ]);
            ?>
            <hr/>
        <?php endif; ?>
        <?php if ($publisherProvider->totalCount): ?>
            <?=
            ProductCarousel::widget([
                'title' => 'หนังสือจากเพื่อนสำนักพิมพ์',
                'subtitle' => Html::a('[ดูทั้งหมด]', ['other-publisher/index']),
                'dataProvider' => $publisherProvider,
            ]);
            ?>
        <?php endif; ?>
    </div>
    <div class="featured-item-block">
        <div class="container">
            <div class="module block-featured-author module-block">
                <div class="module-heading">
                    <h2 class="module-title">Meeting Room <small>บทความ/ข่าว</small></h2>
                </div>
                <div class="module-body">
                    <?=
                    ListView::widget([
                        'layout' => '{items}',
                        'itemView' => '_content',
                        'dataProvider' => $newsProvider,
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (!Yii::$app->session->get('watchedIntro', false) && $intro->isActive): ?>
    <?php
    Modal::begin([
        'id' => 'splash',
        'header' => null,
        'footer' => null,
        'closeButton' => false,
        'size' => 'modal-xl',
        'clientOptions' => [
            'show' => true,
        ],
    ]);
    ?>
    <?php echo Html::a(Html::img($intro->backgroundUrl, ['class' => 'img-resp', 'style' => 'width:100%;']), '#', ['data-dismiss' => 'modal']); ?>
    <?php Yii::$app->session->set('watchedIntro', true); ?>
    <?php Modal::end(); ?>
<?php endif; ?>
<?php JSRegister::begin(); ?>
<script>
    $("#main-slider").slick({
        autoplay: true,
        arrows: true,
        dots: true,
        adaptiveHeight: true,
        autoplaySpeed: <?= Configuration::getValue('web_slide_time', 5000); ?>
    });
</script>
<?php JSRegister::end(); ?>