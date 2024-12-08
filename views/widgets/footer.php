<?php

use app\models\Bank;
use codesk\components\Html;
use yii\widgets\Menu;
?>
<!-- ============================================================= FOOTER ============================================================= -->
<footer class="footer">
    <div class="main-footer">
        <div class="container">
            <div class="row footer-widgets">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <!-- ============================================================= FOOTER SHIPPING DELIVERY ============================================================= -->
                    <div class="module">
                        <div class="module-heading">
                            <h4 class="module-title">ติดต่อสอบถาม</h4>
                        </div><!-- /.module-heading -->

                        <div class="module-body" style="font-size:16px;">
                            <h4 class="text-bold">สำนักพิมพ์มติชน</h4>
                            <p>เลขที่ 12 ถนนเทศบาลนฤมาล หมู่บ้านประชานิเวศน์ 1</p>
                            <p>แขวงลาดยาว เขตจตุจักร กรุงเทพมหานคร 10900</p>
                            <p class="text-bold">ติดต่อฝ่ายขาย 02-589-0020 ต่อ 3350-3360</p>
                            <p><small>(วันจันทร์-วันศุกร์ เวลา 8.30-17.30น.)</small></p>
                        </div><!-- /.module-body -->

                        <hr/>
                        <div class="module-heading">
                            <h4 class="module-title">ติดตามผ่านสื่อออนไลน์</h4>
                        </div><!-- /.module-heading -->
                        <div class="module-body ">
                            <ul class="nav nav-pills social-media-list" id="tooltip">
                                <li class="foot-icon-facebook"><a data-toggle="tooltip" title="Facebook" data-placement="bottom"  href="http://facebook.com/matichonbook" target="_blank"><i class="icon fa fa-facebook"></i></a></li>
                                <li class="foot-icon-line"><a  data-toggle="tooltip" data-placement="bottom" title="Line" href="http://line.me/ti/p/~@matichonbook" target="_blank"><?= Html::img('@web/images/web/icon-line.png', ['width' => '24', 'height' => '29']) ?></a></li>
                                <li class="foot-icon-twitter"><a  data-toggle="tooltip" title="Twitter" data-placement="bottom"  href="https://twitter.com/matichonbooks" target="_blank"><i class="icon fa fa-twitter"></i></a></li>
                                <li class="foot-icon-instagram"><a  data-toggle="tooltip" data-placement="bottom" title="Instagram" href="http://instagram.com/matichonbook/" target="_blank"><i class="icon fa fa-instagram"></i></a></li>
                            </ul><!-- /.nav -->
                        </div><!-- /.module-body -->

                    </div><!-- /.module -->
                    <!-- ============================================================= FOOTER SHIPPING DELIVERY : END ============================================================= -->                </div><!-- /.col -->

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <!-- ============================================================= FOOTER INFORMATION ============================================================= -->
                    <div class="module">
                        <div class="module-heading">
                            <h4 class="module-title">Online Order</h4>
                        </div><!-- /.module-heading -->

                        <div class="module-body">
                            <ul class="list-unstyled list-link">
                                <li><?= Html::a(Html::awesome('shopping-cart') . ' การสั่งสินค้า', ['site/term']); ?></li>
                                <li><?= Html::a(Html::awesome('check') . ' การแจ้งโอนเงิน', ['transfer/index']); ?></li>
                                <li><?= Html::a(Html::awesome('search') . ' การตรวจสอบสถานะ', ['order/index']); ?></li>
                                <li><?= Html::a(Html::awesome('question') . ' ช่วยเหลือ', ['site/help']); ?></li>
                                <li><?= Html::a(Html::awesome('shield') . ' นโยบายคุ้มครองข้อมูลส่วนบุคคล', ['site/privacy-policy']); ?></li>

                            </ul><!-- /.list-link -->
                        </div><!-- /.module-body -->
                    </div><!-- /.module -->
                    <!-- ============================================================= FOOTER INFORMATION : END ============================================================= -->                </div><!-- /.col -->

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <!-- ============================================================= FOOTER QUICK HELP ============================================================= -->
                    <div class="module">
                        <div class="module-heading">
                            <h4 class="module-title">การชำระเงิน
                                <ul class="nav nav-pills payment-list" style="display: inline;">
                                    <?php foreach (Bank::find()->active()->all() as $bank): ?>
                                        <li><?= Html::img($bank->cover_url, ['width' => 32]); ?></li>
                                    <?php endforeach; ?>
                                    <li><?= Html::img('@web/images/payments/visa.png'); ?></li>
                                    <li><?= Html::img('@web/images/payments/mastercard.png'); ?></li>
                                </ul>
                            </h4>
                            <p class="module-subtitle"></p>
                        </div><!-- /.module-heading -->

                        <hr/>
                        <div class="module-heading">
                            <h4 class="module-title">บริการจัดส่ง</h4>
                            <div><?= Html::a(Html::img('@web/images/web/track-tp.png', ['class' => 'img-resp']), 'https://track.thailandpost.co.th', ['target' => '_blank']); ?></div>
                            <div><?= Html::a(Html::img('@web/images/web/track-ap.png', ['class' => 'img-resp']), 'https://www.alphafast.com/th/track?id=', ['target' => '_blank']); ?></div>
                        </div>


                    </div><!-- /.module -->
                    <!-- ============================================================= FOOTER QUICK HELP : END ============================================================= -->
                </div><!-- /.col -->

            </div><!-- /.row -->
            <div class="row footer-widget">
                <div class="col-sm-12">
                    <ul class="list-inline font-xs">
                        <li><strong>เครือมติชน :</strong></li>
                        <li><a href="http://www.khaosod.co.th/default.php" target="_blank">ข่าวสด</a></li>
                        <li><a href="http://www.prachachat.net/default.php" target="_blank">ประชาชาติ</a></li>
                        <li><a href="http://www.matichon.co.th" target="_blank">มติชนออนไลน์</a></li>
                        <li><a href="http://info.matichon.co.th/weekly/" target="_blank">มติชนสุดสัปดาห์</a></li>
                        <li><a href="http://info.matichon.co.th/art/" target="_blank">ศิลปวัฒธรรม</a></li>
                        <li><a href="https://www.technologychaoban.com/home" target="_blank">เทคโนโลยีชาวบ้าน</a></li>
                        <li><a href="https://www.sentangsedtee.com/home" target="_blank">เส้นทางเศรษฐี</a></li>
                        <li><a href="http://www.matichonelibrary.com/" target="_blank">ศูนย์ข้อมูล</a></li>
                        <li><a href="http://www.matichonbook.com" target="_blank">งานดี</a></li>
                        <li><a href="http://www.matichonacademy.com/" target="_blank">มติชนอคาเดมี</a></li>
                    </ul>
                </div>
            </div>
            <!-- ============================================================= FOOTER NAVBAR ============================================================= -->
            <div class="navbar-footer navbar-static-bottom clearfix">
                <p class="navbar-text">Copyright &copy; <?= date('Y'); ?> <span class="navbar-inner-text">MatichonBook</span></p>
                <?=
                Menu::widget([
                    'options' => [
                        'id' => 'example',
                        'class' => 'navbar-nav nav',
                    ],
                    'items' => [
                        [
                            'label' => 'Home',
                            'url' => Yii::$app->homeUrl,
                        ],
                        [
                            'label' => 'FAQ',
                            'url' => ['/site/help'],
                        ],
                        [
                            'label' => 'Support',
                            'url' => ['/site/contact'],
                        ],
                        [
                            'label' => 'Career',
                            'url' => ['/content/page', 'p' => 'ร่วมงานกับเรา'],
                        ],
                    ],
                ]);
                ?>
            </div><!-- /.navbar -->
            <!-- ============================================================= FOOTER NAVBAR : END ============================================================= -->            
        </div><!-- /.container -->
    </div><!-- /.main-footer -->
</footer><!-- /.footer -->