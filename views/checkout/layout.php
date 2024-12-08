<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use kartik\widgets\ActiveForm;
use richardfan\widget\JSRegister;
?>
<div class="content">
    <?=
    Breadcrumbs::widget([
        'items' => [
            [
                'label' => 'ตระกร้าสินค้า',
                'url' => '#',
            ],
        ],
    ]);
    ?>
    <div class="checkout page">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-md-push-7">
                    <?=
                    $this->render('/cart/part/cart', [
                        'model' => $model,
                    ]);
                    ?>
                </div>
                <div class="col-md-7 col-md-pull-5">
                    <?php
                    Page::begin([
                        'title' => 'ขั้นตอนการชำระเงิน',
                        'subtitle' => 'กรุณากรอกข้อมูลให้ถูกต้อง',
                    ]);
                    ?>       
                    <?= $content; ?>
                    <?php Page::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php JSRegister::begin(); ?>
<script>
    $.fn.cartSummary = function () {
        var cart = $(this);

        function refresh() {
            cart.css("opacity", "0.3");
            $.get(cart.data("url"), $('#frm-cart').serialize(), function (data) {
                cart.html(data);
                cart.css("opacity", "1");
            });
        }

        refresh();
    }
    $("#cart-checkout-table").cartSummary();
</script>
<?php JSRegister::end(); ?>