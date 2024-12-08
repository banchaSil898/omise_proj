<?php

use app\components\Html;
use app\models\Country;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $form ActiveForm */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" class="collapsed" data-parent="#accordion" href="#collapseThree">
                <span class="step">3</span>ข้อมูลสำหรับจัดส่งสินค้า
                <span id="step-3" class="pull-right step-icon"></span>
            </a>
        </h4>
    </div><!-- /.panel-heading -->
    <div id="collapseThree" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="form-container">

                <?= $form->field($model, 'delivery_same')->checkbox(); ?>

                <div class="row field-row form-group">
                    <div class="col-xs-12 col-sm-6">
                        <?= $form->field($model, 'delivery_firstname')->textInput(['class' => 'form-control-book']); ?>
                    </div><!-- /.col -->
                    <div class="col-xs-12 col-sm-6">
                        <?= $form->field($model, 'delivery_lastname')->textInput(['class' => 'form-control-book']); ?>
                    </div><!-- /.col -->
                </div><!-- /.row -->

                <div class="row field-row form-group">
                    <div class="col-xs-12">
                        <?= $form->field($model, 'delivery_tax')->label('เลขประจําตัวผู้เสียภาษี ' . Html::tag('small', '*** ไม่จำเป็นต้องกรอก', ['class' => 'text-blur']))->textInput(['class' => 'form-control-book']); ?>
                    </div><!-- /.col -->
                </div><!-- /.row -->

                <div class="row field-row form-group">
                    <div class="col-xs-12">
                        <?= $form->field($model, 'delivery_company')->textInput(['class' => 'form-control-book']); ?>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <div class="row field-row form-group">
                    <div class="col-xs-12">
                        <?=
                        $form->field($model, 'delivery_comment')->textArea([
                            'class' => 'form-control-book',
                            'rows' => 4,
                        ]);
                        ?> 
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <div class="row field-row form-group">
                    <div class="col-xs-12">
                        <?= $form->field($model, 'delivery_address')->label('ที่อยู่ ' . Html::tag('small', '*** กรุณากรอกให้ครบถ้วน', ['class' => 'text-danger']))->textInput(['class' => 'form-control-book']); ?>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <div class="row field-row form-group">
                    <div class="col-xs-12 col-sm-6">
                        <?= $form->field($model, 'delivery_tambon')->label('ตำบล/แขวง')->textInput(['class' => 'form-control-book']); ?>
                    </div><!-- /.col -->
                    <div class="col-xs-12 col-sm-6">
                        <?= $form->field($model, 'delivery_amphur')->label('อำเภอ/เขต')->textInput(['class' => 'form-control-book']); ?>
                    </div><!-- /.col -->
                </div><!-- /.row -->
                <div class="row field-row form-group">
                    <div class="col-xs-12 col-sm-4">
                        <?= $form->field($model, 'delivery_province')->textInput(['class' => 'form-control-book']); ?>
                    </div><!-- /.col -->
                    <div class="col-xs-12 col-sm-4">
                        <?= $form->field($model, 'delivery_postcode')->textInput(['class' => 'form-control-book']); ?>
                    </div><!-- /.col -->
                    <div class="col-xs-12 col-sm-4">
                        <?= $form->field($model, 'delivery_phone')->textInput(['class' => 'form-control-book']); ?>
                    </div><!-- /.col -->
                </div><!-- /.row -->

                <div class="row field-row form-group">
                    <div class="col-xs-12">
                        <?=
                        $form->field($model, 'delivery_country')->dropDownList(ArrayHelper::map(Country::find()->all(), 'country_code', 'name'), [
                            'class' => 'form-control-book',
                            'prompt' => '(เลือกประเทศ)',
                        ]);
                        ?> 
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.form-container -->
            <?=
            Html::submitButton($model->step >= 5 ? 'ชำระเงิน' : 'ขั้นตอนถัดไป ' . Html::awesome('chevron-right'), [
                'name' => 'mode',
                'value' => 'submit',
                'class' => 'btn btn-primary btn-checkout-step pull-right visible-xs',
            ]);
            ?>
        </div><!-- /.panel-body -->
    </div><!-- /.panel-collapse -->
</div><!-- /.panel -->