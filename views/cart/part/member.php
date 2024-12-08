<?php

use app\models\Purchase;
use codesk\components\Html;
use kartik\form\ActiveForm;

$user = Yii::$app->user->identity;
/* @var $form ActiveForm */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                <span class="step">1</span>ข้อมูลบัญชีสมาชิก
                <span id="step-1" class="pull-right step-icon"></span>
            </a>
        </h4>
    </div><!-- /.panel-heading -->

    <div id="collapseOne" class="panel-collapse collapse in">
        <div class="panel-body">           
            <?php if (Yii::$app->user->isGuest): ?>
                <?php foreach (Purchase::getPurchaseTypeCartOptions() as $value => $name): ?>
                    <div class=" checkout-radio-option">                
                        <?=
                        Html::radio('Purchase[purchase_type]', $model->purchase_type == $value, [
                            'id' => 'radio-' . $value,
                            'class' => 'book-radio purchase-type',
                            'value' => $value,
                        ]);
                        ?>
                        <label class="book-radio-label" for="<?= 'radio-' . $value; ?>">
                            <span class="radio-background"><i class="icon fa fa-circle"></i></span> <?= Html::encode($name); ?>
                        </label>                                                                     
                    </div><!-- /.checkout-radio-option -->
                <?php endforeach; ?>
                <div class="form-container">
                    <div class="row field-row form-group">
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->field($model, 'buyer_firstname')->textInput(['class' => 'form-control-book']); ?>

                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->field($model, 'buyer_lastname')->textInput(['class' => 'form-control-book']); ?>
                        </div>
                    </div>
                    <div class="row field-row form-group">
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->field($model, 'buyer_email')->textInput(['class' => 'form-control-book']); ?>

                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <?= $form->field($model, 'buyer_phone')->textInput(['class' => 'form-control-book']); ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?= $form->field($model, 'purchase_type')->label(false)->hiddenInput(); ?>
                <div class="well well-sm text-center">
                    ดำเนินการโดยใช้บัญชี <span class="text-success text-bold"><?= $user->fullname; ?></span><br/><span class="text-primary">(<?= $user->username; ?>)</span>
                </div>
            <?php endif; ?>
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