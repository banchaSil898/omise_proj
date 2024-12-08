<?php

use codesk\components\Html;
use yii\widgets\Pjax;

$cart = $this->context->getCart();
?>
<div id="my-cart" class="collapse cart-white">
    <?php
    Pjax::begin([
        'id' => 'pjax-cart',
    ])
    ?>
    <div class="container">
        <div class="module">
            <div class="module-heading">
                <h4>ตะกร้าสินค้า</h4>
            </div><!-- /.module-heading -->
            <div class="module-body">
                <p>คุณสามารถปรับเปลี่ยนจำนวนสินค้าจากรายการด้านล่างได้</p>
                <div class="order-detail">
                    <table class="table table-cart index-dropdown-table">
                        <thead>
                            <tr>
                                <th class="dark" width="50%">สินค้า</th>
                                <th>ราคา</th>
                                <th class="dark text-center">จำนวน</th>
                                <th>ราคารวม</th>
                                <th class="dark"></th>
                            </tr>
                        </thead><!-- /thead-->

                        <tbody class="index-dropdown-body">
                            <?php if (!$cart->items): ?>
                                <tr class="cart-book">
                                    <td colspan="5" class="text-center">
                                        ไม่มีรายการสินค้าที่เลือกไว้
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cart->items as $item): ?>
                                    <?=
                                    $this->render('/widgets/cart-item', [
                                        'item' => $item,
                                    ]);
                                    ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody><!-- /tbody -->

                    </table><!-- /.table -->
                </div><!-- /.order-detail -->

                <div class="row">
                    <div class="col-sm-offset-3 col-sm-3 center-sm">
                        <!--
                        <input type="text" class="form-control discount-name index-dropdown-discount-name" placeholder="กรอกรหัสคูปอง..">
                        -->
                    </div><!-- /.col -->

                    <div class="col-xs-12 col-sm-6 center-sm">
                        <div class="table-responsive">
                            <table class="table table-cart">
                                <tfoot>
                                    <tr>
                                        <td ></td>
                                        <td ></td>
                                    </tr>
                                    <tr>
                                        <td ><i class="icon-chevron fa fa-chevron-right"></i>&nbsp;ราคารวม:</td>
                                        <td >฿ <?= Yii::$app->formatter->asDecimal($cart->total, 2); ?></td>
                                    </tr>
                                </tfoot><!-- /tfoot -->
                            </table><!-- /.table -->
                        </div><!-- /.table-responsive -->
                    </div> <!-- /.col -->   
                    <div class="col-md-12">
                        <?= Html::a(Html::awesome('arrow-left') . ' กลับไปเลือกสินค้าเพิ่ม', ['category/index'], ['id' => 'btn-cart-close', 'class' => 'pull-left btn btn-default btn-lg', 'data-pjax' => '0']); ?>
                        <?php if ($cart->items): ?>
                            <?= Html::a('ชำระเงิน ' . Html::awesome('chevron-right'), ['cart/checkout'], ['class' => 'pull-right btn btn-primary btn-checkout', 'data-pjax' => '0']); ?>
                        <?php endif; ?>
                    </div>    
                </div><!-- /.row -->

            </div><!-- /.module-body -->
        </div><!-- /.module -->
    </div><!-- /.container -->
    <?php Pjax::end(); ?>
</div><!-- /#my-cart -->