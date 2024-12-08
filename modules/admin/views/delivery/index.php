<?php

use app\models\Country;
use app\models\Purchase;
use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;

$bundle = AppAsset::register($this);
?>
<div class="row">  
    <div class="col-sm-6">
        <?php
        Panel::begin([
            'header' => 'เงื่อนไขค่าจัดส่งสินค้า',
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
        Panel::begin([
            'header' => 'ค่าจัดส่งพัสดุตามน้ำหนัก',
            'icon' => 'truck',
        ]);
        ?>
        <?php
        Pjax::begin([
            'id' => 'pjax-page',
        ]);
        ?>
        <?=
        Tabs::widget([
            'items' => [
                [
                    'label' => 'EMS',
                    'content' => $this->render('_grid', [
                        'type' => Purchase::DELIVERY_EMS,
                        'dataProvider' => $dataProvider,
                    ]),
                    'active' => !Yii::$app->request->get('tab') || (Yii::$app->request->get('tab') === 'xtab' . Purchase::DELIVERY_EMS),
                ],
                [
                    'label' => 'Custom',
                    'content' => $this->render('_grid', [
                        'type' => Purchase::DELIVERY_CUSTOM,
                        'dataProvider' => $customProvider,
                    ]),
                    'active' => Yii::$app->request->get('tab') === 'xtab' . Purchase::DELIVERY_CUSTOM,
                ],
            ],
        ]);
        ?>
        <?php Pjax::end(); ?>
        <?php Panel::end(); ?>
    </div>
    <div class="col-sm-6">
        <?php
        Pjax::begin([
            'id' => 'pjax-airmail',
            'timeout' => 5000,
        ]);
        ?>
        <?php
        Panel::begin([
            'header' => 'ค่าจัดส่งพัสดุ Airmail',
            'icon' => 'plane',
            'tools' => [
                [
                    'encode' => false,
                    'label' => Html::icon('plus') . ' เพิ่มข้อมูล',
                    'url' => ['create-airmail', 'country_id' => $airModel->country_id],
                    'linkOptions' => [
                        'class' => 'btn btn-primary',
                        'data-modal' => '1',
                    ],
                ]
            ],
        ]);
        ?>
        <div class="well well-sm">
            <?php
            $form = ActiveForm::begin([
                        'action' => 'index',
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => '',
                        ],
            ]);
            ?>
            <?= $form->field($airModel, 'country_id')->dropDownList(['*' => '(Default Country)'] + ArrayHelper::map(Country::find()->scopeExistInRate()->orderBy(['iso_alpha3' => SORT_ASC])->all(), 'iso_alpha3', 'name')); ?>
            <?php ActiveForm::end(); ?>
        </div>
        <?=
        GridView::widget([
            'responsiveWrap' => false,
            'dataProvider' => $airDataProvider,
            'columns' => [
                [
                    'attribute' => 'country_id',
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                ],
                [
                    'attribute' => 'weight',
                    'format' => ['decimal', 2],
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
                [
                    'attribute' => 'fee',
                    'format' => ['decimal', 2],
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
                [
                    'header' => 'แก้ไข',
                    'class' => ActionColumn::className(),
                    'template' => '{update}',
                    'buttons' => [
                        'update' => function($url) {
                            return Html::a(Html::icon('pencil'), $url, ['data-modal' => '1', 'data-pjax' => '0']);
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
                    'header' => 'ลบ',
                    'class' => ActionColumn::className(),
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function($url) {
                            return Html::a(Html::icon('trash'), $url, [
                                        'data-pjax' => '0',
                                        'data-ajax' => '1',
                                        'data-ajax-method' => 'post',
                                        'data-ajax-pjax-reload' => '#pjax-airmail',
                                        'data-ajax-confirm' => 'ต้องการลบรายการนี้?',
                            ]);
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
</div> 
<?php
$this->registerJs(<<<JS
    $(document).on('change', '#deliveryrate-country_id', function(){
        $(this).closest('form').submit();
    });
JS
);
?>
<?php \richardfan\widget\JSRegister::begin() ?>
<script>
    $(function () {
        var hash = window.location.hash;
        hash && $('ul.nav a[href="' + hash + '"]').tab('show');

        $('.nav-tabs a').click(function (e) {
            $(this).tab('show');
            window.location.hash = this.hash;
        });
    });

    $(document).on('pjax:success', function () {
        var hash = window.location.hash;
        console.log(hash);

        $('.nav-tabs a[href="' + hash + '"]').tab('show');
    });
</script>
<?php \richardfan\widget\JSRegister::end(); ?>