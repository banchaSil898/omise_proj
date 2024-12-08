<?php

use app\components\Html;
use app\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;
?>
<div class="content">
    <?=
    Breadcrumbs::widget([
        'enableSearch' => false,
        'items' => [
            [
                'label' => 'เข้าสู่ระบบ',
                'url' => ['site/index'],
            ],
        ],
    ]);
    ?>
    <br/>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">ส่งจดหมายยืนยันอีกครั้ง</div>        
            </div>
            <div class="panel-body">
                <?php
                $form = ActiveForm::begin([
                            'layout' => 'horizontal',
                ]);
                ?>
                <?= $form->field($model, 'username')->textInput(); ?>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <?= Html::submitButton(Html::icon('envelope') . ' ส่งอีเมล์', ['class' => 'btn btn-primary pull-right']); ?>
                        <?= Html::a(Html::icon('arrow-left') . ' ย้อนกลับหน้าแรก', ['site/index'], ['class' => 'btn btn-default']); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>