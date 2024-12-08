<?php

use app\models\Configuration;
use app\models\Product;
use app\modules\admin\components\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput;
use yii\redactor\widgets\Redactor;
?>
<?php
$this->beginContent('@module/views/product/layouts/form.php', [
    'model' => $model,
]);
?>
<?php
$form = ActiveForm::begin([
            'type' => 'horizontal',
            'enableClientValidation' => false,
        ])
?>
<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-lg-8">
        <?= $form->field($model, 'product_type')->dropDownList(Product::getProductTypeOptions()); ?>
        <?= $form->field($model, 'name')->textInput(); ?>
        <?= $form->field($model, 'sku')->textInput(); ?>
        <?= $form->field($model, 'isbn')->textInput(); ?>
        <?=
        $form->field($model, 'info_author')->widget(Select2::classname(), [
            'options' => [
                'multiple' => true,
                'placeholder' => 'กรอกข้อมูลผู้เขียน ...',
            ],
            'pluginOptions' => [
                'tags' => true,
                'allowClear' => true
            ],
        ]);
        ?>
        <?=
        $form->field($model, 'info_compiled')->widget(Select2::classname(), [
            'options' => [
                'multiple' => true,
                'placeholder' => 'กรอกข้อมูลผู้เรียบเรียง ...',
            ],
            'pluginOptions' => [
                'tags' => true,
                'allowClear' => true
            ],
        ]);
        ?>
        <?=
        $form->field($model, 'info_translate')->widget(Select2::classname(), [
            'options' => [
                'multiple' => true,
                'placeholder' => 'กรอกข้อมูลผู้แปล ...',
            ],
            'pluginOptions' => [
                'tags' => true,
                'allowClear' => true
            ],
        ]);
        ?>
        <?= $form->field($model, 'publisher_name')->textInput(); ?>
        <?= $form->field($model, 'info_cover')->textInput(); ?>
        <?= $form->field($model, 'info_publish')->textInput(); ?>
        <?= $form->field($model, 'info_paper')->textInput(); ?>
        <?=
        $form->field($model, 'brief')->widget(Redactor::className(), [
            'clientOptions' => [
                'imageManagerJson' => ['/redactor/upload/image-json'],
                'imageUpload' => ['/redactor/upload/image'],
                'fileUpload' => ['/redactor/upload/file'],
                'plugins' => ['clips', 'fontcolor', 'fontsize', 'imagemanager', 'video']
            ]
        ]);
        ?>
        <?=
        $form->field($model, 'description')->widget(Redactor::className(), [
            'clientOptions' => [
                'imageManagerJson' => ['/redactor/upload/image-json'],
                'imageUpload' => ['/redactor/upload/image'],
                'fileUpload' => ['/redactor/upload/file'],
                'plugins' => ['clips', 'fontcolor', 'fontsize', 'imagemanager', 'video']
            ]
        ]);
        ?>
        <?=
        $form->field($model, 'info_page', [
            'addon' => [
                'append' => [
                    'content' => 'หน้า',
                ],
            ],
        ])->textInput();
        ?>
        <?=
        $form->field($model, 'info_width', [
            'addon' => [
                'append' => [
                    'content' => 'cm',
                ],
            ],
        ])->textInput();
        ?>
        <?=
        $form->field($model, 'info_height', [
            'addon' => [
                'append' => [
                    'content' => 'cm',
                ],
            ],
        ])->textInput();
        ?>
        <?=
        $form->field($model, 'info_depth', [
            'addon' => [
                'append' => [
                    'content' => 'cm',
                ],
            ],
        ])->textInput();
        ?>
        <?=
        $form->field($model, 'info_weight', [
            'addon' => [
                'append' => [
                    'content' => 'g',
                ],
            ],
        ])->textInput();
        ?>

    </div>
    <div class="col-lg-4">
        <div class="well well-sm">
            <h4>ตั้งค่าสถานะสินค้า</h4>
            <?php
            $form->formConfig['labelSpan'] = 6;
            ?>
            <?=
            $form->field($model, 'is_new')->widget(SwitchInput::className());
            ?>
            <?=
            $form->field($model, 'is_recommended')->widget(SwitchInput::className());
            ?>
            <?=
            $form->field($model, 'is_bestseller')->widget(SwitchInput::className());
            ?>
            <?=
            $form->field($model, 'is_promotion')->widget(SwitchInput::className());
            ?>
            <?=
            $form->field($model, 'free_shipping')->widget(SwitchInput::className());
            ?>
            <?=
            $form->field($model, 'is_out_of_stock')->widget(SwitchInput::className());
            ?>
            <?=
            $form->field($model, 'is_pin')->widget(SwitchInput::className());
            ?>
        </div>
        <!--
        <div class="well well-sm">
            <h4>ตั้งค่าการจัดส่ง</h4>
            <?php
            $form->formConfig['labelSpan'] = 6;
            ?>
            <?=
            $form->field($model, 'is_delivery_std')->widget(SwitchInput::className());
            ?>
            <?=
            $form->field($model, 'delivery_std_cost', [
                'addon' => [
                    'append' => [
                        'content' => '฿',
                    ],
                ],
            ])->textInput();
            ?>
            <?=
            $form->field($model, 'delivery_register_cost', [
                'addon' => [
                    'append' => [
                        'content' => '฿',
                    ],
                ],
            ])->textInput([
                'placeholder' => 'ค่าปกติ : ' . Configuration::getValue('delivery_thaipost'),
            ]);
            ?>
            <p class="well well-sm">
                <strong>หมายเหตุ</strong> กรณีที่ไม่ระบุ ระบบจะใช้ค่าตามที่กำหนดไว้ในเมนูตั้งค่าการจัดส่ง (<?= Yii::$app->formatter->asDecimal(Configuration::getValue('delivery_thaipost'), 2); ?> บาท)
            </p>
        </div>
        -->
    </div>
</div>
<hr/>
<div class="form-group">
    <div class="col-md-10 col-md-offset-2">
        <?=
        Html::submitButton([
            'class' => 'pull-right',
        ]);
        ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>
