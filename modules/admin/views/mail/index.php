<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;
?>

<div class="row">
    <div class="col-sm-8">
        <?php
        Pjax::begin([
            'id' => 'pjax-page',
        ]);
        ?>
        <?php
        Panel::begin([
            'header' => 'ตั้งค่ารูปแบบอีเมล์',
            'icon' => 'envelope',
        ]);
        ?>
        <?=
        GridView::widget([
            'responsiveWrap' => false,
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label' => 'จดหมาย',
                    'attribute' => 'name',
                ],
                [
                    'label' => 'หัวเรื่อง',
                    'attribute' => 'mail_title',
                ],
                [
                    'header' => 'แก้ไข',
                    'class' => ActionColumn::className(),
                    'template' => '{update}',
                    'buttons' => [
                        'update' => function($url) {
                            return Html::a(Html::icon('pencil'), $url, ['data-modal' => '1', 'data-modal-size' => 'lg', 'data-pjax' => '0']);
                        },
                    ],
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                ],
                [
                    'header' => 'แสดง',
                    'class' => ActionColumn::className(),
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function($url) {
                            return Html::a(Html::icon('eye-open'), $url, ['data-modal' => '1', 'data-modal-size' => 'lg', 'data-pjax' => '0']);
                        },
                    ],
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                ],
            ],
        ]);
        ?>
        <?php Panel::end(); ?>
        <?php Pjax::end(); ?>
    </div>
    <div class="col-sm-4">
        <?php
        Panel::begin([
            'header' => 'ตั้งค่า Header / Footer',
            'icon' => 'envelope',
        ]);
        ?>
        <div>
            <?= Html::a(Html::awesome('edit') . ' แก้ไขจดหมายทั่วไป', ['layout-update'], ['class' => 'btn btn-default', 'data-modal' => '1', 'data-modal-size' => 'lg']); ?>
            <?= Html::a('แสดงตัวอย่าง', ['layout-preview'], ['class' => 'btn btn-primary pull-right', 'data-modal' => '1', 'data-modal-size' => 'lg']); ?>
        </div>
        <hr/>
        <div>
            <?= Html::a(Html::awesome('edit') . ' แก้ไขใบเสร็จ', ['layout-update', 'mode' => 'receipt'], ['class' => 'btn btn-default', 'data-modal' => '1', 'data-modal-size' => 'lg']); ?>
            <?= Html::a('แสดงตัวอย่าง', ['layout-preview', 'mode' => 'receipt'], ['class' => 'btn btn-primary pull-right', 'data-modal' => '1', 'data-modal-size' => 'lg']); ?>
        </div>
        <?php Panel::end(); ?>
        <?php
        Panel::begin([
            'header' => 'Mail Server Configuration',
            'icon' => 'cog',
        ]);
        ?>
        <?=
        GridView::widget([
            'responsiveWrap' => false,
            'dataProvider' => $configDataProvider,
            'columns' => [
                [
                    'attribute' => 'description',
                ],
                [
                    'class' => EditableColumn::className(),
                    'attribute' => 'data',
                    'editableOptions' => function($model, $key, $index) {
                        $options = [];
                        $options['inputType'] = $model->config_type;

                        if ($model->config_type === 'dropDownList') {
                            $items = explode(',', $model->config_options);
                            foreach ($items as $item) {
                                $options['data'][$item] = $item;
                            }
                        }
                        return $options;
                    },
                ],
            ],
        ]);
        ?>
        <?php Panel::end(); ?>
        <?php
        $form = ActiveForm::begin([
                    'action' => ['test'],
        ]);
        ?>
        <?php
        Panel::begin([
            'header' => 'ทดสอบการส่งจดหมาย',
            'icon' => 'share',
        ]);
        ?>
        <div class="input-group">
            <div class="input-group-addon">
                <?= Html::awesome('envelope'); ?>
            </div>
            <?= Html::input('text', 'email', '', ['class' => 'form-control', 'placeholder' => 'กรุณากรอกอีเมล์...']); ?>
        </div>
        <div>
            <?= Html::submitButton('ทดสอบ', ['class' => 'btn btn-primary pull-right']); ?>
        </div>
        <?php Panel::end(); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>