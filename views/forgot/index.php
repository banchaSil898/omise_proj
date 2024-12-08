<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use yii\bootstrap\ActiveForm;
?>
<?=
Breadcrumbs::widget([
    'enableSearch' => false,
    'items' => [
        [
            'label' => 'ลืมรหัสผ่าน',
            'url' => ['/forgot/index'],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => 'ลืมรหัสผ่าน',
        'subtitle' => 'กรุณากรอกอีเมล์ที่คุณเคยสมัครไว้กับเรา'
    ]);
    ?>
    <?php
    $form = ActiveForm::begin([
                'layout' => 'horizontal',
    ]);
    ?>
    <?= $form->field($model, 'username')->textInput(['autoFocus' => true]); ?>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <?= Html::submitButton(Html::awesome('pencil') . ' ตรวจสอบข้อมูล', ['class' => 'btn btn-primary pull-right']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Page::end(); ?>
</div>