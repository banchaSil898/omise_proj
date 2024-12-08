<?php

use app\models\Bank;
use app\models\Configuration;
use codesk\components\Html;
use kartik\form\ActiveForm;

/* @var $form ActiveForm */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a  data-toggle="collapse" class="collapsed" data-parent="#accordion" href="#collapsePaymentInformation">
                <span class="step">5</span>ข้อมูลการชำระเงิน
                <span id="step-5" class="pull-right step-icon"></span>
            </a>
        </h4>
    </div><!-- /.panel-heading -->
    <div id="collapsePaymentInformation" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="form-container">
                    <div style="padding:15px;">
                        <?php
                        $payments = [];
                        $banks = Bank::find()->active()->all();
                        $bankItems = [];
                        foreach ($banks as $bank) {
                            $bankItems[] = Html::img($bank->cover_url, ['width' => 32]);
                        }
                        $payments[0] = 'โอนผ่านธนาคาร<br/>' . implode(' ', $bankItems) . ' (* โปรดอย่าโอนเงินจนกว่าจะได้รับเลขที่ใบสั่งซื้อ)<br/><br/>';

                        $rate = Configuration::getValue('web_creditcard_min', 0);
                        if (!$rate || ($rate && $model->price_grand >= $rate)) {
                            $payments[1] = 'ชำระผ่านบัตรเครดิต<br/>' . implode(' ', [
                                        Html::img('@web/images/payments/visa.png'),
                                        Html::img('@web/images/payments/mastercard.png'),
                            ]);
                        }
                        ?>
                        <?=
                        $form->field($model, 'payment_method')->label('กรุณาเลือกวิธีการชำระเงิน')->radioList($payments);
                        ?>
                        <?=
                        Html::submitButton('ชำระเงิน ' . Html::awesome('chevron-right'), [
                            'name' => 'mode',
                            'value' => 'submit',
                            'class' => 'btn btn-primary btn-checkout-step pull-right visible-xs',
                        ]);
                        ?>
                        <div class="visible-xs clearfix" style="margin-bottom: 30px;"></div>
                        <!--
                        <?=
                        $this->render('/widgets/transfer', [
                            'form' => $form,
                            'model' => $model,
                        ]);
                        ?>-->
                    </div>
                </div><!-- /.form-container -->
            </div>
        </div><!-- /.panel-body -->
    </div><!-- /.panel-collapse -->
</div><!-- /.panel -->