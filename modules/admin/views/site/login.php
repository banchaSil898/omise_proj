<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
?>
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <?php
                $form = ActiveForm::begin([
                            'enableClientValidation' => false,
                ]);
                ?>
                <?= Html::img($this->context->module->assetUrl . '/images/micbook-logo.png', ['style' => 'margin-bottom:15px;']); ?>
                <h3>ระบบบริหารจัดการสินค้าออนไลน์</h3>
                <?=
                $form->field($model, 'username', [
                    'addon' => [
                        'prepend' => [
                            'content' => Html::icon('user'),
                        ],
                    ],
                ])->label(false)->textInput([
                    'autoFocus' => true,
                ]);
                ?>
                <?=
                $form->field($model, 'password', [
                    'addon' => [
                        'prepend' => [
                            'content' => Html::icon('lock'),
                        ],
                    ],
                ])->label(false)->passwordInput();
                ?>
                <?= Html::submitButton(Html::icon('log-in') . ' เข้าสู่ระบบ', ['class' => 'btn btn-primary']); ?>
                <?= Html::a(Html::icon('home') . ' ไปยังหน้าร้าน', Yii::$app->homeUrl, ['class' => 'btn btn-warning']); ?>
                <div class="clearfix"></div>
                <div class="separator">
                    <p>&copy;<?= date('Y'); ?> All Rights Reserved. MatichonBook.com</p>
                </div>
                <?php ActiveForm::end(); ?>
            </section>
        </div>
    </div>
</div>

