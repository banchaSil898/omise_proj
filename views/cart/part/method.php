<?php

use app\models\Purchase;
use codesk\components\Html;
use kartik\form\ActiveForm;

/* @var $form ActiveForm */
$cart = $this->context->getCart();
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a  data-toggle="collapse" class="collapsed" data-parent="#accordion" href="#collapseShippingDetails">
                <span class="step">4</span>วิธีการจัดส่ง
                <span id="step-4" class="pull-right step-icon"></span>
            </a>
        </h4>
    </div><!-- /.panel-heading -->

    <div id="collapseShippingDetails" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="form-container">
                <div id="delivery-pane">
                    <?=
                    $form->field($model, 'delivery_method')->label(false)->radioList($cart->getDeliveryOptionsByPurchase($model), [
                        'unchecked' => null,
                        'class' => 'btn-shipping',
                    ]);
                    ?>
                </div>
                <?=
                Html::submitButton($model->step >= 5 ? 'ชำระเงิน' : 'ขั้นตอนถัดไป ' . Html::awesome('chevron-right'), [
                    'name' => 'mode',
                    'value' => 'submit',
                    'class' => 'btn btn-primary btn-checkout-step pull-right visible-xs',
                ]);
                ?>
            </div><!-- /.form-container-->
        </div><!-- /.panel-body -->
    </div><!-- /.panel-collapse -->
</div><!-- /.panel -->