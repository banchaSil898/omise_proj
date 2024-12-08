<?php

use app\models\Folder;
use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use kop\y2sp\ScrollPager;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$categories = Folder::find()->andWhere(['level' => 0])->all();
$items = [];
$items[] = [
    'label' => 'หมวดหมู่หนังสือ',
    'url' => ['category/index'],
];
if (isset($oCategory)) {
    $items[] = [
        'label' => $oCategory->name,
        'url' => ['category/index', 'id' => $oCategory->id],
    ];
}
if (isset($oCategoryItem)) {
    $items[] = [
        'label' => $oCategoryItem->name,
        'url' => ['#'],
    ];
}
?>
<div class="content">
    <?=
    Breadcrumbs::widget([
        'items' => $items,
        'enableSearch' => true,
    ]);
    ?>
    <div class="category page">
        <div class="container">
            <?php
            Page::begin([
                'title' => 'หมวดหมู่หนังสือ',
                'subtitle' => 'เลือกหนังสือจากหมวดหมู่ต่างๆ',
            ]);
            ?>

            <div class="row">
                <!-- ========================================= CONTENT ========================================= -->
                <div class="col-sm-9 col-sm-push-3">
                    แสดงหนังสือจากหมวดหมู่ <span class="text-primary"><?= isset($oCategory) ? $oCategory->name : 'ทั้งหมด'; ?></span> <?= isset($oCategoryItem) ? ' / ' . $oCategoryItem->name : ''; ?>
                    <?php if (isset($model->search['text'])): ?>
                        โดยคำค้น <span class="text-primary">&quot;<?= Html::encode($model->search['text']); ?>&quot;</span>
                    <?php endif; ?>
                    <hr/>
                    <div class="tab-content">
                        <div class="tab-pane active  wow fadeInUp" id="grid" role="tabpanel">
                            <div class="category-books books grid-view">
                                <div class="row">
                                    <?php $pjax = Pjax::begin(); ?>
                                    <?=
                                    ListView::widget([
                                        'id' => 'book-grid',
                                        'itemOptions' => [
                                            'class' => 'item col-md-4 col-sm-6'
                                        ],
                                        'layout' => '{items} {pager}',
                                        'itemView' => '/widgets/book',
                                        'dataProvider' => $dataProvider,
                                        'pager' => [
                                            'class' => ScrollPager::className(),
                                            'enabledExtensions' => [
                                                ScrollPager::EXTENSION_SPINNER,
                                                //ScrollPager::EXTENSION_NONE_LEFT,
                                                ScrollPager::EXTENSION_PAGING,
                                            ],
                                        ],
                                    ]);
                                    ?>
                                    <?php Pjax::end(); ?>
                                </div>
                            </div><!-- /.category-books -->
                        </div><!-- /.tab-pane -->


                    </div><!-- /.tab-content -->
                </div><!-- /.col -->

                <!-- ========================================= CONTENT :END ========================================= -->

                <!-- ========================================= SIDEBAR ========================================= -->
                <div class="col-sm-3 col-sm-pull-9">
                    <aside class="sidebar">
                        <!-- ========================================= BOOK CATEGORY ========================================= -->
                        <section class="module">
                            <header class="module-header">
                                <h4 class="module-book-category-title">หมวดหนังสือ</h4>
                            </header><!-- /.module-header -->

                            <div class="module-body category-module-body">
                                <ul class="list-unstyled category-list">
                                    <li class="sub-category-list <?= (!Yii::$app->request->get('id') ? 'active' : ''); ?>">
                                        <?= Html::a('(แสดงทุกหมวดหมู่)', ['category/index'], ['class' => 'text-bold']) ?>
                                    </li>
                                    <?php foreach ($categories as $category): ?>
                                        <li class="sub-category-list dropdown <?= (Yii::$app->request->get('id') == $category->id ? 'active' : ''); ?>">
                                            <?= Html::a(Html::encode($category->name), ['category/index', 'id' => $category->id]); ?>
                                            <?php if (count($category->categoryItems)): ?>
                                                <ul class="list-unstyled dropdown-menu dropdown-menu-right">
                                                    <?php foreach ($category->categoryItems as $item): ?>
                                                        <li><?= Html::a(Html::encode($item->name), ['category/index', 'id' => $item->id], ['class' => 'dropdown-item']); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul><!-- /.list-unstyled -->
                            </div><!-- /.module-body -->
                        </section><!-- /.module -->
                        <!-- ========================================= BOOK CATEGORY : END ========================================= -->                    </aside><!-- /.sidebar -->
                </div><!-- /.col -->
                <!-- ========================================= SIDEBAR :END ========================================= -->
            </div><!-- /.row -->
            <?php Page::end() ?>
        </div><!-- /.container -->
    </div><!-- /.category page -->

    <a class="scrollup hidden-xs hidden-sm" href="#" style="display: none;">
        <?= Html::img('@web/images/top-scroll.png'); ?>
    </a>
</div>
