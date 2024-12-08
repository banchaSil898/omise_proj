<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $form ActiveForm */
$promotion = $cart->getPromotionSummary();
?>
<?php if (isset($promotion['products'])): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a  data-toggle="collapse" class="collapsed" data-parent="#accordion" href="#collapsePromotion">
                    <span class="step">6</span>เงื่อนไขโปรโมชั่น
                    <span id="step-6" class="pull-right step-icon"></span>
                </a>
            </h4>
        </div><!-- /.panel-heading -->
        <div id="collapsePromotion" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="row">
                    <div class="form-container">
                        <?php foreach ($promotion['info'] as $info): ?>
                            <?php if (isset($info['products'])): ?>
                                <div style="padding:15px;">
                                    <h3 class="text-primary"><?= ArrayHelper::getValue($info, 'name'); ?></h3>
                                    <p>มีสิทธิเลือกของแถมได้จำนวน <?= ArrayHelper::getValue($info, 'attributes.product_count', 0); ?> เล่ม</p>
                                    <table class="table table-sm table-bordered table-condensed">
                                        <?php foreach ($info['products'] as $product): ?>
                                            <tr>
                                                <td class="text-center" style="padding:0;"><?= Html::img($product->coverUrl, ['height' => 64]); ?></td>
                                                <td><?= Html::encode($product->name); ?></td>
                                                <td class="text-center" >
                                                    <?= Html::a('เลือกสินค้า', ['promotion/product-add', 'promotion_id' => $info['id'], 'product_id' => $product->id], ['class' => 'btn btn-success btn-add-promotion']); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div><!-- /.form-container -->
                </div>
            </div><!-- /.panel-body -->
        </div><!-- /.panel-collapse -->
    </div><!-- /.panel -->
<?php endif; ?>