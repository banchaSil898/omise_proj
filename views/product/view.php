<?php

use app\models\Product;
use app\widgets\Breadcrumbs;
use app\widgets\Page;
use app\widgets\ProductCarousel;
use app\components\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this View */

$this->context->title = htmlspecialchars_decode($model->name);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => implode(', ', $model->meta_keywords),
]);
$this->registerMetaTag([
    'name' => 'description',
    'content' => $model->meta_description,
]);
$this->registerMetaTag([
    'property' => 'fb:app_id',
    'content' => Yii::$app->params['facebook']['appId'],
]);
$this->registerMetaTag([
    'property' => 'og:title',
    'content' => htmlspecialchars_decode($model->name),
]);
$this->registerMetaTag([
    'property' => 'og:url',
    'content' => Url::to(['view', 'id' => $model->id], true),
]);
$this->registerMetaTag([
    'property' => 'og:image',
    'content' => Url::to($model->cover_url, true),
]);
$this->registerMetaTag([
    'property' => 'og:image:width',
    'content' => 300,
]);
$this->registerMetaTag([
    'property' => 'og:image:height',
    'content' => 420,
]);
$this->registerMetaTag([
    'property' => 'og:type',
    'content' => 'book',
]);
$this->registerMetaTag([
    'property' => 'og:description',
    'content' => Html::encode(strip_tags($model->brief)),
]);
$this->registerMetaTag([
    'property' => 'book:isbn',
    'content' => Html::encode($model->isbn),
]);
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'หนังสือ',
            'url' => ['/category/index'],
        ],
        [
            'label' => htmlspecialchars_decode($model->name),
            'url' => $model->seoUrl,
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
    ]);
    ?>
    <?php
    Pjax::begin([
        'id' => 'pjax-page',
    ]);
    ?>
    <div class="btn-toolbar" style="margin-bottom:30px;">
        <?php if ($model->ebookUrl): ?>
            <?= Html::a(Html::awesome('shopping-cart') . ' ทดลองอ่าน', ['/product/preview', 'id' => $model->id], ['class' => 'btn btn-info pull-right', 'data-modal' => '1', 'data-modal-size' => 'lg']); ?>
        <?php endif; ?>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?=
            Html::a(Html::awesome('heart-o') . ' ถูกใจเล่มนี้', ['/product/love', 'id' => $model->id], [
                'class' => 'pull-right btn btn-' . ($model->isLove ? 'danger' : 'default'),
                'data-toggle' => 'tooltip',
                'title' => 'ถูกใจ',
                'data-ajax' => '1',
                'data-ajax-method' => 'post',
                'data-ajax-pjax-reload' => '#pjax-page',
                'data-pjax' => '0',
            ]);
            ?>
        <?php endif; ?>
    </div>
    <?php Pjax::end(); ?>
    <div class="row">

        <div class="col-sm-4">
            <div class="text-center"> 
                <?= Html::img($model->coverUrl, ['class' => 'img-resp book-border product-img']); ?>          
                <?php if ($model->isCartable): ?>
                    <div>
                        <?php if ($model->currentPrice <> $model->price): ?>
                            <h3 class="book-price text-info" style="text-decoration: line-through;margin-bottom:0;">฿ <?= Yii::$app->formatter->asDecimal(Html::encode($model->price), 2); ?></h3>
                        <?php endif; ?>
                        <h3 class="book-price text-danger">฿ <?= Yii::$app->formatter->asDecimal($model->currentPrice, 2); ?></h3>
                        <?php if ($model->isOutOfStock): ?>
                            <?= Html::a('สินค้าหมด', 'javascript:void(0);', ['class' => 'btn btn-danger btn-out-of-stock btn-lg']); ?>
                        <?php else: ?>
                            <?php if ($model->hasAddOns): ?>
                                <?= Html::a(Html::awesome('shopping-cart') . ' หยิบใส่รถเข็น', ['cart/select-addon', 'id' => $model->id], ['class' => 'btn btn-primary', 'data-modal' => 1, 'data-pjax' => 0]); ?>
                            <?php else : ?>
                                <?= Html::a(Html::awesome('shopping-cart') . ' หยิบใส่รถเข็น', ['cart/add', 'id' => $model->id], ['class' => 'btn btn-primary btn-add-cart']); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <hr/>
                <?php endif; ?>
                <?=
                DetailView::widget([
                    'model' => $model,
                    'options' => [
                        'class' => 'table detail-view book-detail',
                    ],
                    'attributes' => [
                        [
                            'attribute' => 'isbn',
                            'visible' => isset($model->isbn) && $model->isbn,
                        ],
                        [
                            'attribute' => 'info_cover',
                            'visible' => isset($model->info_cover) && $model->info_cover,
                        ],
                        [
                            'attribute' => 'info_paper',
                            'visible' => isset($model->info_paper) && $model->info_paper,
                        ],
                        [
                            'attribute' => 'info_page',
                            'visible' => isset($model->info_page) && $model->info_page,
                            'value' => function($data) {
                                return $data->info_page . ' <small>หน้า</small>';
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'info_weight',
                            'visible' => isset($model->info_weight) && $model->info_weight,
                            'value' => function($data) {
                                return $data->info_weight . ' <small>กรัม</small>';
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'info_width',
                            'visible' => isset($model->info_width) && $model->info_width,
                            'value' => function($data) {
                                return $data->info_width . ' <small>ซม.</small>';
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'info_height',
                            'visible' => isset($model->info_height) && $model->info_height,
                            'value' => function($data) {
                                return $data->info_height . ' <small>ซม.</small>';
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'info_depth',
                            'visible' => isset($model->info_depth) && $model->info_depth,
                            'value' => function($data) {
                                return $data->info_depth . ' <small>ซม.</small>';
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'info_publish',
                            'visible' => isset($model->info_publish) && $model->info_publish,
                        ],
                        [
                            'attribute' => 'info_publisher',
                            'visible' => isset($model->info_publisher) && $model->info_publisher,
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="col-sm-8">
            <h1 class="text-title">
                <?= Html::encode($model->name); ?>
            </h1>
            <?php if (count($model->info_author)): ?>
                <h4 class="product-meta text-danger">ผู้เขียน : <?= Html::encode(implode(', ', $model->info_author)); ?></h4>
            <?php endif; ?>
            <?php if (count($model->info_translate)): ?>
                <h4 class="product-meta text-danger">แปลโดย : <?= Html::encode(implode(', ', $model->info_translate)); ?></h4>
            <?php endif; ?>
            <?php if (count($model->info_compiled)): ?>
                <h4 class="product-meta text-danger">เรียบเรียงโดย : <?= Html::encode(implode(', ', $model->info_compiled)); ?></h4>
            <?php endif; ?>
            <?= $model->getHtmlFlags(); ?>
            <hr/>
            <div class="product-brief-box">
                <div class="alert">
                    <?= Html::content($model->brief); ?>
                </div>

            </div>
            <div class="product-description-box">
                <?= Html::content($model->description); ?>
            </div>

            <?php if (isset($itemProvider)): ?>
                <?php if ($itemProvider->totalCount): ?>
                    <div class="bundle-pane">
                        <?=
                        ProductCarousel::widget([
                            'title' => 'ชุดนี้ประกอบด้วยสินค้า ' . Html::tag('span', Yii::$app->formatter->asInteger($itemProvider->totalCount), ['class' => 'text-primary']) . ' รายการ',
                            'dataProvider' => $itemProvider,
                            'itemOptions' => [
                                'showPrice' => $model->product_type == Product::TYPE_FOLDER ? true : false,
                                'showCartButton' => $model->product_type == Product::TYPE_FOLDER ? true : false,
                            ],
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (count($model->productImages)): ?>
                <hr/>
                <h3>รูปอื่นๆ</h3>
                <div>
                    <?php foreach ($model->productImages as $item): ?>
                        <?= Html::a(Html::img($item->thumbUrl, ['width' => 64]), ['image', 'product_id' => $item->product_id, 'image_id' => $item->image_id], ['data-modal' => 1, 'data-modal-size' => 'lg', 'class' => 'thumb-link pull-left']); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>    <h3 class="text-success"></h3>
    <?php if ($relateProvider->totalCount): ?>
        <hr/>
        <?=
        ProductCarousel::widget([
            'title' => 'หนังสือที่เกี่ยวข้อง',
            'dataProvider' => $relateProvider,
        ]);
        ?>
    <?php endif; ?>
    <?php Page::end(); ?>
</div>