<?php

use app\models\Folder;
use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use kop\y2sp\ScrollPager;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$categories = Folder::find()->defaultScope()->all();
?>
<div class="content">
    <?=
    Breadcrumbs::widget([
        'items' => [],
        'enableSearch' => true,
    ]);
    ?>
    <div style="min-height:200px;height:30vh;background-position:50% 50%;background-size:contain;background-repeat:no-repeat; background-image:url(<?= $slide->imageUrl; ?>);">
    </div>
    <div class="category page">
        <div class="container">
            <div class="page-header category-page-header">
                <h2 class="page-title text-center"><?= Html::encode($slide->name); ?></h2>
            </div> 
            <div class="page-body">
                <div>
                    <?= $slide->html; ?>
                </div>


                <div class="row">
                    <!-- ========================================= CONTENT ========================================= -->
                    <div class="col-sm-12">


                        <div class="tab-content">
                            <div class="tab-pane active  wow fadeInUp" id="grid" role="tabpanel">
                                <div class="category-books books grid-view">
                                    <div class="row">
                                        <?php $pjax = Pjax::begin(); ?>
                                        <?=
                                        ListView::widget([
                                            'id' => 'book-grid',
                                            'itemOptions' => [
                                                'class' => 'item col-md-2 col-sm-4'
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

                    <!-- ========================================= SIDEBAR :END ========================================= -->
                </div><!-- /.row -->
            </div>
        </div><!-- /.container -->
    </div><!-- /.category page -->
    <a class="scrollup hidden-xs hidden-sm" href="#" style="display: none;">
        <?= Html::img('@web/images/top-scroll.png'); ?>
    </a>
</div>