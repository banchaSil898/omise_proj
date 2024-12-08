<?php

use app\models\Bank;
use codesk\components\Html;

$banks = Bank::find()->active()->all();
?>
<div id="bank-info" class="well well-sm">
    <p>ชำระเงินโดยการโอนเงินผ่านธนาคาร – ชื่อบัญชี : <span class="text-primary text-bold">บริษัท งานดี จำกัด</span></p>
    <div class="media-list">
        <?php foreach ($banks as $bank): ?>
            <div class="media-object" style="padding:10px;">
                <div class="media-left">
                    <?= Html::img($bank->cover_url, ['class' => 'media-object', 'style' => 'width:60px']); ?>
                </div>
                <div class="media-body">
                    <h4 class="media-heading" style="padding-top:5px;"><span class="text-primary"><?= Html::encode($bank->name); ?></span> <small>สาขา<?= Html::encode($bank->account_branch); ?> (<?= Html::encode($bank->account_type); ?>)</small></h4>
                    <p>หมายเลขบัญชี : <?= Html::encode($bank->account_no); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>