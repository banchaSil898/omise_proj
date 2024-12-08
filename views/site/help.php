<?php

use app\models\ContactType;
use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use himiklab\yii2\recaptcha\ReCaptcha;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yiister\gentelella\widgets\Accordion;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'ช่วยเหลือ',
            'url' => ['site/help'],
        ]
    ],
]);
?>
<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <?php
            Page::begin([
                'title' => 'คำถามที่พบบ่อย (FAQ)',
            ])
            ?>
            <?=
            Accordion::widget([
                'id' => 'faq-list',
                'items' => $faqItems,
            ]);
            ?>
            <?php Page::end(); ?>
        </div>
        <div class="col-sm-4">
            <?php
            Page::begin([
                'title' => 'ข้อมูลสำหรับติดต่อกลับ',
            ])
            ?>
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($contact, 'name')->textInput(); ?>
            <?= $form->field($contact, 'email')->textInput(); ?>
            <?=
            $form->field($contact, 'contact_type_id')->dropDownList(ArrayHelper::map(ContactType::find()->scopeDefault()->all(), 'id', 'name'), [
                'prompt' => '(กรุณาเลือกหัวข้อ)',
            ]);
            ?>
            <?= $form->field($contact, 'purchase_no')->textInput(); ?>
            <?= $form->field($contact, 'description')->textArea(['rows' => 5]); ?>
            <?= $form->field($contact, 'reCaptcha')->label(false)->widget(ReCaptcha::className()) ?>
            <div class="text-right">
                <?= Html::submitButton(Html::icon('envelope') . ' ส่งข้อความ', ['class' => 'btn btn-primary']); ?>
            </div>
            <?php ActiveForm::end(); ?>
            <?php Page::end(); ?>
        </div>
    </div>
</div>