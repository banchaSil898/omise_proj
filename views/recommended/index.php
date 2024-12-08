<?php

use app\models\Category;
use codesk\components\Html;
use yii\widgets\ListView;

$categories = Category::find()->all();
?>
<div class="content">
    <div class="breadcrumb-container">
        <div class="container">
            <ul class="breadcrumb pull-left">
                <li>
                    <?= Html::a('หน้าหลัก', ['category/index']); ?>
                </li>
                <li>
                    <a href='#'>หนังสือแนะนำ</a>
                </li>
            </ul><!-- /.breadcrumb -->

            <!-- ========================================= BREADCRUMB SEARCH BOX ========================================= -->
            <ul class="list-unstyled search-box pull-right">
                <li data-target="#search" data-toggle="sub-header"><button type="button" class="btn btn-primary-dark search-button"><i class="fa fa-search icon"></i></button>
                    <div class="row search-action sub-header" id="search">
                        <div class="col-sm-8 col-xs-12 no-padding-right">
                            <div class="input-group">
                                <span class="input-group-btn"><button class="btn btn-search" type="button"><i class="fa fa-search icon"></i></button></span>
                                <input type="text" class="form-control search-book" placeholder="Search books...">
                            </div><!-- /.input-group -->
                        </div><!-- /.col -->

                        <div class="col-sm-4 col-xs-12 select-wrapper" style="padding:0px;">
                            <select id="id_select" class="selectpicker">
                                <option selected>All Category</option>
                                <option>Books</option>
                                <option>Textbooks</option>
                                <option>Audiobooks</option>
                                <option>Magazines</option>
                                <option>Kids</option>
                            </select>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </li>
            </ul><!-- /.search-box -->
            <!-- ========================================= BREADCRUMB SEARCH BOX : END ========================================= -->        

        </div><!-- /.container -->
    </div><!-- /.breadcrumb-container -->

    <div class="category page">
        <div class="container">
            <div class="page-header category-page-header">
                <h2 class="page-title">หนังสือแนะนำ</h2>
                <p class="page-subtitle">รายชื่อหนังสือน่าสนใจ คัดสรรเพื่อนักอ่านทุกท่าน</p>
            </div><!-- /.page-header -->

            <div class="page-body">
                <div class="row">
                    <!-- ========================================= CONTENT ========================================= -->
                    <div class="col-sm-12">


                        <div class="tab-content">
                            <div class="tab-pane active  wow fadeInUp" id="grid" role="tabpanel">
                                <div class="category-books books grid-view">
                                    <div class="row">
                                        <?=
                                        ListView::widget([
                                            'itemView' => '/widgets/book',
                                            'dataProvider' => $dataProvider,
                                        ]);
                                        ?>
                                    </div>
                                </div><!-- /.category-books -->
                            </div><!-- /.tab-pane -->

                            <div class="tab-pane wow fadeInUp" id="list" role="tabpanel">
                                <div class="featured-book">
                                    <div class="books clearfix">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-5">
                                                <div class="book">
                                                    <div class="hot-ribbon"><div class="hot-ribbon-content">hot</div></div>
                                                    <div class="book-cover">
                                                        <img class="img-responsive" alt="" width="193" height="261" src="assets/images/books/4.jpg">
                                                        <div class="fade"></div>
                                                        <div class="book-price">
                                                            <span class="price">$ 9.99</span>
                                                        </div><!-- /.book-price -->
                                                    </div><!-- /.book-cover -->
                                                </div><!-- /.book -->
                                            </div><!-- /.col -->

                                            <div class="col-md-8 col-sm-7">
                                                <div class="book-details book-details-list-view">
                                                    <h3 class="book-title">
                                                        <a href="detail.html">Adipisicing</a>
                                                    </h3>
                                                    <p class="book-author">dolor sit</p>
                                                    <div class="star-rating">
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star"></i>
                                                    </div><!-- /.star-rating -->                                
                                                </div><!-- /.book-details -->

                                                <div class="featured-book-content">
                                                    <p class="hidden-sm hidden-md">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. </p>

                                                </div><!-- /.featured-book-content -->
                                                <div class="actions">
                                                    <a class="add-to-cart" title="Add to Cart" href="checkout.html"><i class="icon-plus fa fa-plus-circle"></i></a>
                                                    <a class="add-to-cart" title="Favourite" href="#"><i class="icon-heart fa fa-heart"></i></a>
                                                </div><!-- /.actions -->
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.books -->
                                </div><!-- /.row -->

                                <div class="featured-book">
                                    <div class="books clearfix">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-5">
                                                <div class="book">
                                                    <div class="book-cover">
                                                        <img class="img-responsive" alt="" width="193" height="261" src="assets/images/books/10.jpg">
                                                        <div class="fade"></div>
                                                        <div class="book-price">
                                                            <span class="price">$ 9.99</span>
                                                        </div><!-- /.book-price -->
                                                    </div><!-- /.book-cover -->
                                                </div><!-- /.book -->
                                            </div><!-- /.col -->

                                            <div class="col-md-8 col-sm-7">
                                                <div class="book-details book-details-list-view">
                                                    <h3 class="book-title">
                                                        <a href="detail.html">Elit sed do</a>
                                                    </h3>
                                                    <p class="book-author">consectetur</p>
                                                    <div class="star-rating">
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star"></i>
                                                    </div><!-- /.star-rating -->
                                                </div><!-- /.book-details -->
                                                <div class="featured-book-content">
                                                    <p class="hidden-sm hidden-md">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. </p>

                                                </div><!-- /.featured-book-content -->
                                                <div class="actions">
                                                    <a class="add-to-cart" title="Add to Cart" href="checkout.html"><i class="icon-plus fa fa-plus-circle"></i></a>
                                                    <a class="add-to-cart" title="Favourite" href="#"><i class="icon-heart fa fa-heart"></i></a>
                                                </div><!-- /.actions -->
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.books -->
                                </div><!-- /.row -->

                                <div class="featured-book">
                                    <div class="books clearfix">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-5">
                                                <div class="book">
                                                    <div class="book-cover">
                                                        <img class="img-responsive" width="193" height="261" alt="" src="assets/images/books/3.jpg">
                                                        <div class="fade"></div>
                                                        <div class="book-price">
                                                            <span class="price">$ 9.99</span>
                                                        </div><!-- /.book-price -->
                                                    </div><!-- /.book-cover -->
                                                </div><!-- /.book -->
                                            </div><!-- /.col -->

                                            <div class="col-md-8 col-sm-7">
                                                <div class="book-details book-details-list-view">
                                                    <h3 class="book-title">
                                                        <a href="detail.html">Paradise Lost</a>
                                                    </h3>
                                                    <p class="book-author">adipisicing soibhan</p>
                                                    <div class="star-rating">
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star"></i>
                                                    </div><!-- /.star-rating -->
                                                </div><!-- /.book-details -->
                                                <div class="featured-book-content">
                                                    <p class="hidden-sm hidden-md">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. </p>

                                                </div><!-- /.featured-book-content -->
                                                <div class="actions">
                                                    <a class="add-to-cart" title="Add to Cart" href="checkout.html"><i class="icon-plus fa fa-plus-circle"></i></a>
                                                    <a class="add-to-cart" title="Favourite" href="#"><i class="icon-heart fa fa-heart"></i></a>
                                                </div><!-- /.actions -->
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->

                                    </div><!-- /.books -->
                                </div><!-- /.row -->

                                <div class="featured-book">
                                    <div class="books clearfix">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-5">
                                                <div class="book">
                                                    <div class="book-cover">
                                                        <img class="img-responsive" width="193" height="261" alt="" src="assets/images/books/12.jpg">
                                                        <div class="fade"></div>
                                                        <div class="book-price">
                                                            <span class="price">$ 9.99</span>
                                                        </div><!-- /.book-price -->
                                                    </div><!-- /.book-cover -->
                                                </div><!-- /.book -->
                                            </div><!-- /.col -->

                                            <div class="col-md-8 col-sm-7">
                                                <div class="book-details book-details-list-view">
                                                    <h3 class="book-title">
                                                        <a href="detail.html">Guy Kawasaki</a>
                                                    </h3>
                                                    <p class="book-author">sed do</p>
                                                    <div class="star-rating">
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star color"></i>
                                                        <i class="fa fa-star"></i>
                                                    </div><!-- /.star-rating -->
                                                </div><!-- /.book-details -->
                                                <div class="featured-book-content">
                                                    <p class="hidden-sm hidden-md">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. </p>

                                                </div><!-- /.featured-book-content -->
                                                <div class="actions">
                                                    <a class="add-to-cart" title="Add to Cart" href="checkout.html"><i class="icon-plus fa fa-plus-circle"></i></a>
                                                    <a class="add-to-cart" title="Favourite" href="#"><i class="icon-heart fa fa-heart"></i></a>
                                                </div><!-- /.actions -->
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.books -->
                                </div><!-- /.row -->
                            </div><!-- /.tab-pane -->

                        </div><!-- /.tab-content -->
                    </div><!-- /.col -->

                    <!-- ========================================= CONTENT :END ========================================= -->

                    <!-- ========================================= SIDEBAR ========================================= -->

                    <!-- ========================================= SIDEBAR :END ========================================= -->
                </div><!-- /.row -->
            </div><!-- /.page-body -->
        </div><!-- /.container -->
    </div><!-- /.category page -->

    <a class="scrollup hidden-xs hidden-sm" href="#" style="display: none;"><img src="assets/images/top-scroll.png" alt="" ></a>
</div>
