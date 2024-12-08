<?php

use app\components\Html;
use app\widgets\Breadcrumbs;
use app\widgets\Page;
use kartik\form\ActiveForm;
use richardfan\widget\JSRegister;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$cart = $this->context->getCart();
$user = Yii::$app->user->identity;
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
    <?php
    $form = ActiveForm::begin([
                'id' => 'frm-cart',
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
                'validateOnChange' => false,
                'validateOnBlur' => false,
                'validationUrl' => ['checkout', 'mode' => 'validate'],
                'options' => [
                    'data-step' => $model->step,
                ],
    ]);
    ?>
    <div class="checkout page">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-md-push-7">
                    <?=
                    $this->render('part/cart', [
                        'form' => $form,
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
                    <div class="panel-group panel-cart" id="accordion">
                        <?=
                        $this->render('part/member', [
                            'form' => $form,
                            'model' => $model,
                        ]);
                        ?>

                        <?=
                        $this->render('part/invoice', [
                            'form' => $form,
                            'model' => $model,
                        ]);
                        ?>

                        <?=
                        $this->render('part/delivery', [
                            'form' => $form,
                            'model' => $model,
                        ]);
                        ?>

                        <?=
                        $this->render('part/method', [
                            'form' => $form,
                            'model' => $model,
                        ]);
                        ?>

                        <?=
                        $this->render('part/payment', [
                            'form' => $form,
                            'model' => $model,
                        ]);
                        ?>

                        <?=
                        $this->render('part/promotion', [
                            'form' => $form,
                            'model' => $model,
                            'cart' => $cart,
                        ]);
                        ?>
                    </div><!-- /.panel-group -->

                    <div id="cart-coupon">
                        <?php if ($model->coupon_id): ?>
                            <div class="alert alert-success">
                                <h4 class="text-bold">รหัสส่วนลด :</h4>
                                <h3 class="text-primary text-center"><?= $model->coupon_code; ?></h3>
                                <div class="text-center">
                                    <div><?= $model->coupon_detail; ?></div>
                                    <?= Html::a('ยกเลิกคูปอง', ['cart/coupon-remove'], ['class' => 'btn btn-sm btn-danger', 'id' => 'btn-remove-coupon']) ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <?=
                            $form->field($model, 'coupon_code', [
                                'addon' => [
                                    'append' => [
                                        'content' => Html::button('ตรวจสอบ', [
                                            'id' => 'btn-apply-coupon',
                                            'name' => 'mode',
                                            'value' => 'validate',
                                        ]),
                                    ],
                                ],
                            ])->label('กรอกรหัสส่วนลด')->textInput([
                                'class' => 'text-center',
                            ]);
                            ?>
                        <?php endif; ?>
                    </div>
                    <div class="error-summary">
                        <?= $form->errorSummary($model); ?>
                    </div>
                    <div class="place-order-button">
                        <?=
                        Html::submitButton($model->step >= 5 ? 'ชำระเงิน' : 'ขั้นตอนถัดไป ' . Html::awesome('chevron-right'), [
                            'name' => 'mode',
                            'value' => 'submit',
                            'class' => 'btn btn-primary btn-checkout hidden-xs',
                            'id' => 'btn-cart-checkout',
                        ]);
                        ?>
                    </div>
                    <?php Page::end(); ?>
                </div>

            </div>
        </div>
    </div><!-- /.checkout page -->
    <?php ActiveForm::end(); ?>
</div>
<?php
$cartUrl = Url::to(['cart/checkout']);
$couponUrl = Url::to(['cart/apply-coupon']);
$registerUrl = Url::to(['register/index', 'url' => Url::to(['cart/checkout'])]);
$loginUrl = Url::to(['site/login', 'url' => Url::to(['cart/checkout'])]);
$giftRememberUrl = Url::to(['cart/gift-remember']);
?>
<?php JSRegister::begin(); ?>
<script>
    $(document).on('change', '.dd-gift-change', function () {
        var obj = $(this);
        $.post('<?= $giftRememberUrl; ?>', {
            id: obj.data('id'),
            attribute: obj.data('attribute'),
            value: obj.val()
        }, function () {
            cartDisplay();
            console.log('saved ' + obj.val() + ' to session.');
        });
        console.log($(this).val());
    });

    $(document).on('click', '.btn-add-promotion', function () {
        var btn = $(this);
        $(btn).prop('disabled', true);
        $.post(btn.attr('href'), function (data) {
            $.alert(data.msg);
            $.get('<?= $cartUrl; ?>', function (cData) {
                cartDisplay();
            });
            $(btn).prop('disabled', false);
        });
        return false;
    });

    $(document).on('click', '.btn-remove-promotion', function () {
        var btn = $(this);
        $(btn).prop('disabled', true);
        $.post(btn.attr('href'), function (data) {
            $.get('<?= $cartUrl; ?>', function (cData) {
                cartDisplay();
            });
            $(btn).prop('disabled', false);
        });
        return false;
    });




    $(document).on('click', '#btn-apply-coupon', function () {
        var btn = $(this);
        $(btn).prop('disabled', true);
        $.post('<?= $couponUrl; ?>', {code: $('#purchase-coupon_code').val()}, function (data) {
            if (data.success) {
                $.get('<?= $cartUrl; ?>', function (cData) {
                    $('#cart-coupon').html($('#cart-coupon', cData).html());
                    cartDisplay();
                });
            } else {
                $('.field-purchase-coupon_code .help-block').html(data.message);
            }
            $(btn).prop('disabled', false);
        });
        return false;
    });

    $(document).on('click', '#btn-remove-coupon', function () {
        $.post($(this).attr('href'), {code: $('#purchase-coupon_code').val()}, function (data) {
            if (data.success) {
                $.get('<?= $cartUrl; ?>', function (cData) {
                    $('#cart-coupon').html($('#cart-coupon', cData).html());
                    cartDisplay();
                });
            } else {
                $('.field-purchase-coupon_code .help-block').html(data.message);
            }
        });
        return false;
    });

    $("#collapseOne, #collapseTwo, #collapseThree, #collapseShippingDetails, #collapsePaymentInformation, #collapsePromotion").on("shown.bs.collapse", function () {
        var myEl = $(this).prev('.panel-heading');
        console.log($(myEl));
        $('html, body').animate({
            scrollTop: $(myEl).offset().top
        }, 500);
    });

    function togglePanel(step) {
        switch (step) {
            case 1:
                $('#collapseOne').collapse('show');
                $('#collapseTwo').collapse('hide');
                $('#collapseThree').collapse('hide');
                $('#collapseShippingDetails').collapse('hide');
                $('#collapsePaymentInformation').collapse('hide');
                $('#collapsePromotion').collapse('hide');
                break;
            case 2:
                $('#collapseTwo').collapse('show');
                $('#collapseOne').collapse('hide');
                $('#collapseThree').collapse('hide');
                $('#collapseShippingDetails').collapse('hide');
                $('#collapsePaymentInformation').collapse('hide');
                $('#collapsePromotion').collapse('hide');
                break;
            case 3:
                $('#collapseThree').collapse('show');
                $('#collapseOne').collapse('hide');
                $('#collapseTwo').collapse('hide');
                $('#collapseShippingDetails').collapse('hide');
                $('#collapsePaymentInformation').collapse('hide');
                $('#collapsePromotion').collapse('hide');
                break;
            case 4:
                $('#collapseOne').collapse('hide');
                $('#collapseTwo').collapse('hide');
                $('#collapseThree').collapse('hide');
                $('#collapseShippingDetails').collapse('show');
                $('#collapsePaymentInformation').collapse('hide');
                $('#collapsePromotion').collapse('hide');
                break;
            case 5:
                $('#collapseOne').collapse('hide');
                $('#collapseTwo').collapse('hide');
                $('#collapseThree').collapse('hide');
                $('#collapseShippingDetails').collapse('hide');
                $('#collapsePaymentInformation').collapse('show');
                $('#collapsePromotion').collapse('hide');
                break;
            case 6:
                $('#collapsePromotion').collapse('show');
                $('#collapseOne').collapse('hide');
                $('#collapseTwo').collapse('hide');
                $('#collapseThree').collapse('hide');
                $('#collapseShippingDetails').collapse('hide');
                $('#collapsePaymentInformation').collapse('hide');
                break;
        }
    }

    $(document).ready(function () {

        $('#frm-cart')
                .on('afterValidate', function (event, fields, errors) {
                    if (fields.step > $(this).data('step')) {
                        $(this).data('step', $(this).data('step') + 1);
                    } else {
                        $(this).data('step', fields.step);
                    }
                    togglePanel($(this).data('step'));

                    $('#btn-cart-checkout').html('ขั้นตอนถัดไป <span class="fa fa-chevron-right"></span>');
                    if (fields.step > 1) {
                        $('#step-1').html('<span class="text-success"><span class="glyphicon glyphicon-ok"></span></span>');
                    }
                    if (fields.step > 2) {
                        $('#step-2').html('<span class="text-success"><span class="glyphicon glyphicon-ok"></span></span>');
                    }
                    if (fields.step > 3) {
                        $('#step-3').html('<span class="text-success"><span class="glyphicon glyphicon-ok"></span></span>');
                    }
                    if (fields.step > 4) {
                        $('#step-4').html('<span class="text-success"><span class="glyphicon glyphicon-ok"></span></span>');
                        $('#btn-cart-checkout').html('ชำระเงิน <span class="fa fa-chevron-right"></span>');
                    }
                    if (fields.step > 5) {
                        $('#step-5').html('<span class="text-success"><span class="glyphicon glyphicon-ok"></span></span>');
                        $('#btn-cart-checkout').html('ชำระเงิน <span class="fa fa-chevron-right"></span>');
                    }
                    if (fields.step > 6) {
                        $('#step-6').html('<span class="text-success"><span class="glyphicon glyphicon-ok"></span></span>');
                    }
                    console.log(fields, errors);

                    if (errors.length) {
                        console.log('have error');
                        event.preventDefault();
                        return false;
                    }
                    return true;
                });

        $(document).on('change', '#frm-cart', function () {
            console.log('form chnaged.');
            formProcess();
        });

        $(document).on('change', '#purchase-invoice_country, #purchase-delivery_country', function () {
            $.post($(this).closest('form').attr('action') + '?mode=update', $(this).closest('form').serialize(), function (data) {
                console.log('update delivery method');
                $('#delivery-pane').html($('#delivery-pane', data).html());
                cartDisplay();
            });
        });

        $(document).on('change', '.btn-shipping', function () {
            cartDisplay();
        });

        $(document).on('change', '.purchase-type', function () {
            switch ($(this).val()) {
                case '0':
                    break;
                case '1':
                    window.location.href = '<?= $registerUrl; ?>';
                    break;
                case '2':
                    window.location.href = '<?= $loginUrl; ?>';
                    break;
            }
        });

        formProcess();
        cartDisplay();
    });

    function cartDisplay() {
        var cart = $("#cart-checkout-table");
        cart.css("opacity", "0.3");
        $.get(cart.data("url"), $('#frm-cart').serialize(), function (data) {
            cart.html(data);
            cart.css("opacity", "1");
        });
    }

    function formProcess() {
        if ($("#purchase-delivery_same").prop('checked')) {
            $("#purchase-delivery_firstname").prop("disabled", true);
            $("#purchase-delivery_lastname").prop("disabled", true);
            $("#purchase-delivery_idcard").prop("disabled", true);
            $("#purchase-delivery_company").prop("disabled", true);
            $("#purchase-delivery_tax").prop("disabled", true);
            $("#purchase-delivery_address").prop("disabled", true);
            $("#purchase-delivery_tambon").prop("disabled", true);
            $("#purchase-delivery_amphur").prop("disabled", true);
            $("#purchase-delivery_province").prop("disabled", true);
            $("#purchase-delivery_postcode").prop("disabled", true);
            $("#purchase-delivery_phone").prop("disabled", true);
            $("#purchase-delivery_country").prop("disabled", true);
            $("#purchase-delivery_comment").prop("disabled", true);

            $("#purchase-delivery_firstname").val($("#purchase-invoice_firstname").val());
            $("#purchase-delivery_lastname").val($("#purchase-invoice_lastname").val());
            $("#purchase-delivery_idcard").val($("#purchase-invoice_idcard").val());
            $("#purchase-delivery_company").val($("#purchase-invoice_company").val());
            $("#purchase-delivery_tax").val($("#purchase-invoice_tax").val());
            $("#purchase-delivery_address").val($("#purchase-invoice_address").val());
            $("#purchase-delivery_tambon").val($("#purchase-invoice_tambon").val());
            $("#purchase-delivery_amphur").val($("#purchase-invoice_amphur").val());
            $("#purchase-delivery_province").val($("#purchase-invoice_province").val());
            $("#purchase-delivery_postcode").val($("#purchase-invoice_postcode").val());
            $("#purchase-delivery_phone").val($("#purchase-invoice_phone").val());
            $("#purchase-delivery_country").val($("#purchase-invoice_country").val());
            $("#purchase-delivery_comment").val($("#purchase-invoice_comment").val());
        } else {
            $("#purchase-delivery_firstname").prop("disabled", false);
            $("#purchase-delivery_lastname").prop("disabled", false);
            $("#purchase-delivery_idcard").prop("disabled", false);
            $("#purchase-delivery_company").prop("disabled", false);
            $("#purchase-delivery_tax").prop("disabled", false);
            $("#purchase-delivery_address").prop("disabled", false);
            $("#purchase-delivery_tambon").prop("disabled", false);
            $("#purchase-delivery_amphur").prop("disabled", false);
            $("#purchase-delivery_province").prop("disabled", false);
            $("#purchase-delivery_postcode").prop("disabled", false);
            $("#purchase-delivery_phone").prop("disabled", false);
            $("#purchase-delivery_country").prop("disabled", false);
            $("#purchase-delivery_comment").prop("disabled", false);
        }

        var choice = $('input[name="Purchase[payment_method]"]:checked').val();
        if (choice === '0') {
            $('#bank-info').show();
        } else {
            $('#bank-info').hide();
        }
    }

</script>
<?php JSRegister::end(); ?>
<?php if (isset($survey2019) && $survey2019->enabled): ?>
    <?php $survey2019->doMarkSession(); ?>
    <?php
    Modal::begin([
        'id' => 'survey-2019-modal',
        'clientOptions' => [
            'show' => true,
        ],
        'header' => Html::tag('h3', 'ถ้าคุณมีโอกาสถามคุณธนินท์ 1 คำถาม คุณจะถามว่าอะไร'),
    ]);
    ?>
    <?php
    $form = ActiveForm::begin([
                'id' => 'survey-2019-frm',
                'type' => 'horizontal',
                'action' => ['survey/save'],
                'fieldConfig' => [
                    'labelSpan' => 3
                ],
    ]);
    ?>
    <?=
    $form->field($survey2019, 'age')->dropDownList($survey2019->getAgeOptions(), [
        'prompt' => '(กรุณาเลือก)',
    ]);
    ?>
    <?=
    $form->field($survey2019, 'graduate')->dropDownList($survey2019->getGraduateOptions(), [
        'prompt' => '(กรุณาเลือก)',
    ]);
    ?>
    <?= $form->field($survey2019, 'comment')->textarea(['rows' => 4]); ?>
    <div class="form-group">
        <div class="col-md-9 col-md-offset-3">
             <p>*** เงื่อนไข จะได้ลุ้นรับสิทธิ์เข้าร่วมฟังเสวนา เมื่อชำระเงินเสร็จเรียบร้อยแล้ว</p>
        </div>
    </div>
    <hr/>
    <?= Html::submitButton('สนใจเข้าร่วมฟังเสวนา', ['class' => 'btn btn-success btn-lg pull-right']); ?>
    <?= Html::button('ไม่สนใจ', ['class' => 'btn btn-default btn-lg', 'data-dismiss' => 'modal']); ?>
    <?php ActiveForm::end(); ?>
    <?php Modal::end(); ?>
    <?php JSRegister::begin(); ?>
    <script>
        $('#survey-2019-frm').on('beforeSubmit', function () {
            var isError;
            var strError = new Array;

            isError = false;

            if (!$('#survey2019-age').val()) {
                isError = true;
                strError.push('กรุณาเลือกช่วงอายุ');
            }
            if (!$('#survey2019-graduate').val()) {
                isError = true;
                strError.push('กรุณาเลือกช่วงการศึกษา');
            }
            if (isError) {
                $.alert(strError.join("\n"));
            } else {
                $.post($(this).attr('action'), $(this).serialize(), function (data) {
                    if (data.result) {
                        $('#survey-2019-modal').modal('hide');
                        $.alert('ขอบคุณสำหรับคำถาม   ประกาศรายชื่อผู้ได้รับสิทธิ์ ในวันพุธที่ 2 ตุลาคม 2562 ผ่านทาง eMail Facebook-Matichon Book สำนักพิมพ์มติชน   www.matichonbook.com');
                    }
                });
            }
            return false;
        });
    </script>
    <?php JSRegister::end(); ?>
<?php endif; ?>
