<?php

use app\components\grid\DeleteButtonColumn;
use app\models\Folder;
use app\models\Product;
use app\models\Publisher;
use app\modules\admin\assets\AppAsset;
use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\CheckboxColumn;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use richardfan\widget\JSRegister;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;

$categories = Folder::find()->andWhere(['level' => 0])->all();
$bundle = AppAsset::register($this);
?>
<?php
Panel::begin([
    'header' => 'จัดการสินค้า',
    'icon' => 'cubes',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มสินค้า',
            'url' => ['create'],
            'linkOptions' => [
                'class' => 'btn btn-primary',
            ],
        ]
    ],
]);
?>
<?php
Pjax::begin([
    'id' => 'pjax-page',
    'timeout' => 5000
]);
?>
<div class="row" id="product-data-grid">
    <div class="col-sm-2">
        <ul class="category-nav">
            <li><?= Html::a('หนังสือทั้งหมด' . '<small class="text-primary pull-right">(' . Yii::$app->formatter->asInteger(Product::find()->count()) . ')</small>', ['index'], ['class' => 'text-bold']); ?>
            <li>
                <?=
                Html::a('สำนักพิมพ์มติชน' . '<small class="text-primary pull-right">(' . Yii::$app->formatter->asInteger(Product::find()->isOwner()->count()) . ')</small>', ['index', 'Product' => [
                        'is_own' => '1',
                    ]], ['class' => 'text-bold']);
                ?>
            </li>
            <li>
                <?=
                Html::a('สำนักพิมพ์อื่น' . '<small class="text-primary pull-right">(' . Yii::$app->formatter->asInteger(Product::find()->isNotOwner()->count()) . ')</small>', ['index', 'Product' => [
                        'is_own' => '0',
                    ]], ['class' => 'text-bold']);
                ?>
            </li>
            <li><hr/></li>
            <li>
                <?=
                Html::a('หนังสือใหม่' . '<small class="text-primary pull-right">(' . Yii::$app->formatter->asInteger(Product::find()->isNew()->count()) . ')</small>', ['index', 'Product' => [
                        'is_new' => '1',
                    ]], ['class' => 'text-bold']);
                ?>
            </li>
            <li>
                <?=
                Html::a('หนังสือขายดี' . '<small class="text-primary pull-right">(' . Yii::$app->formatter->asInteger(Product::find()->isBestSeller()->count()) . ')</small>', ['index', 'Product' => [
                        'is_bestseller' => '1',
                    ]], ['class' => 'text-bold']);
                ?>
            </li>
            <li>
                <?=
                Html::a('หนังสือแนะนำ' . '<small class="text-primary pull-right">(' . Yii::$app->formatter->asInteger(Product::find()->isRecommended()->count()) . ')</small>', ['index', 'Product' => [
                        'is_recommended' => '1',
                    ]], ['class' => 'text-bold']);
                ?>
            </li>
            <li>
                <?=
                Html::a('หนังสือหมดสต๊อก' . '<small class="text-primary pull-right">(' . Yii::$app->formatter->asInteger(Product::find()->isOutOfStock()->count()) . ')</small>', ['index', 'Product' => [
                        'is_out_of_stock' => '1',
                    ]], ['class' => 'text-bold']);
                ?>
            </li>
            <li>
                <?=
                Html::a('หนังสือที่ซ่อนไว้' . '<small class="text-primary pull-right">(' . Yii::$app->formatter->asInteger(Product::find()->isHide()->count()) . ')</small>', ['index', 'Product' => [
                        'is_hide' => '1',
                    ]], ['class' => 'text-bold']);
                ?>
            </li>
            <li><hr/></li>            
            <?php foreach ($categories as $category): ?>
                <li class="<?= (isset($model->search['only_folder_id']) && $model->search['only_folder_id'] == $category->id) ? 'active' : ''; ?>">
                    <?=
                    Html::a(Html::encode($category->name) . '<small class="text-primary pull-right">(' . Yii::$app->formatter->asInteger($category->getProductFolders()->count()) . ')</small>', ['index', 'Product' => [
                            'search' => [
                                'only_folder_id' => $category->id,
                            ],
                        ]], [
                        'class' => 'text-bold',
                    ]);
                    ?>
                    <?php if (count($category->categoryItems)): ?>
                        <ul style="padding-left:15px;">
                            <?php foreach ($category->categoryItems as $item): ?>
                                <li>
                                    <?=
                                    Html::a(Html::encode($item->name) . '<small class="text-primary pull-right">(' . Yii::$app->formatter->asInteger($item->getProductFolders()->count()) . ')</small>', ['index', 'Product' => [
                                            'search' => [
                                                'only_folder_id' => $item->id,
                                            ],
                                    ]]);
                                    ?>      
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            </li>
        </ul>
    </div>
    <div class="col-sm-10">
        <?php
        $form = ActiveForm::begin([
                    'action' => ['index'],
                    'type' => 'inline',
                    'method' => 'get',
        ]);
        ?>
        <?=
        $form->field($model, 'search[text]', [
            'autoPlaceholder' => false,
            'addon' => [
                'prepend' => [
                    'content' => Html::icon('search'),
                ]
            ],
            'inputOptions' => [
                'style' => 'width:280px',
                'placeholder' => 'ชื่อหนังสือ, ชื่อผู้แต่ง, ISBN, สำนักพิมพ์'
            ],
        ])->textInput();
        ?> 
        <?=
        $form->field($model, 'is_hide')->dropDownList([
            '0' => 'แสดง',
            '1' => 'ซ่อน',
                ], [
            'prompt' => '(ทุกสถานะ)',
        ]);
        ?> 
        <?=
        $form->field($model, 'search[condition]')->dropDownList([
            'outofstock' => 'สินค้าหมดสต๊อค',
            'lowstock' => 'สินค้าใกล้หมดสต๊อค (1-10)',
            'nofolder' => 'ยังไม่ได้กำหนดหมวดหมู่',
                ], [
            'prompt' => '(ไม่กำหนดเงื่อนไข)',
        ]);
        ?> 
        <?= Html::submitButton('ค้นหา', ['class' => 'btn btn-info btn-flat']); ?>
        <?php ActiveForm::end(); ?>
        <?php
        $form = ActiveForm::begin([
                    'id' => 'operation-frm',
                    'action' => ['index'],
                    'type' => 'inline',
                    'method' => 'get',
        ]);
        ?>
        <div class="well well-sm">
            <?=
            Html::button('ใหม่', [
                'class' => 'btn btn-operator btn-info',
                'data-flag' => 'new',
                'data-url' => Url::to(['set-flag']),
            ]);
            ?>
            <?=
            Html::button('แนะนำ', [
                'class' => 'btn btn-operator btn-warning',
                'data-flag' => 'recommended',
                'data-url' => Url::to(['set-flag']),
            ]);
            ?>
            <?=
            Html::button('ขายดี', [
                'class' => 'btn btn-operator btn-success',
                'data-flag' => 'bestseller',
                'data-url' => Url::to(['set-flag']),
            ]);
            ?>
            <?=
            Html::button('แสดง', [
                'class' => 'btn btn-operator btn-primary',
                'data-flag' => 'show',
                'data-url' => Url::to(['set-flag']),
            ]);
            ?>
            <?=
            Html::button(Html::awesome('trash') . ' ลบ', [
                'id' => 'btn-bulk-delete',
                'class' => 'btn btn-operator btn-danger pull-right',
                'data-url' => Url::to(['bulk-delete']),
            ]);
            ?>
        </div>
        <?php ActiveForm::end(); ?>
        <?=
        GridView::widget([
            'id' => 'product-grid',
            'resizableColumns' => false,
            'responsiveWrap' => false,
            'striped' => false,
            'condensed' => true,
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $ret = [];
                if ($model->is_hide) {
                    Html::addCssClass($ret, 'row-hide');
                }
                return $ret;
            },
            'columns' => [
                [
                    'class' => SerialColumn::className(),
                    'headerOptions' => [
                        'width' => 32,
                    ],
                ],
                [
                    'class' => CheckboxColumn::className(),
                ],
                [
                    'attribute' => 'cover_url',
                    'value' => function($model) {
                        return Html::a(Html::img($model->thumbUrl ? $model->thumbUrl : Html::placeholder(48, 65), ['width' => 48]), ['update', 'id' => $model->id], ['data-pjax' => '0']);
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'width' => 64,
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'style' => 'padding:2px;',
                        'class' => 'text-center',
                    ],
                ],
                [
                    'attribute' => 'name',
                    'value' => function($model) {
                        $html = '{product}<div><small class="text-muted">{publisher}</small></div><div>{label}</div>';

                        $replace = [
                            '{product}' => Html::a(Html::encode($model->name), ['update', 'id' => $model->id], ['data-pjax' => '0']),
                            '{publisher}' => isset($model->publisher) ? Html::encode($model->publisher->name) : '',
                            '{label}' => $model->htmlFlags,
                        ];
                        return str_replace(array_keys($replace), array_values($replace), $html);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'price',
                    'value' => function($model) {
                        return Html::tag('div', Yii::$app->formatter->asDecimal($model->price_sell, 2), ['class' => 'text-success text-bold']) . ($model->price_sell <> $model->price ? Html::tag('div', Yii::$app->formatter->asDecimal($model->price, 2), ['class' => 'text-strike text-muted']) : '');
                    },
                    'headerOptions' => [
                        'width' => 100,
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                    'format' => 'html',
                ],
                [
                    'class' => EditableColumn::className(),
                    'refreshGrid' => true,
                    'editableOptions' => [
                        'formOptions' => [
                            'action' => ['update-stock-inline']
                        ],
                        'options' => [
                            'name' => 'stock',
                        ]
                    ],
                    'attribute' => 'stock',
                    'format' => 'integer',
                    'headerOptions' => [
                        'width' => 100,
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
                [
                    'attribute' => 'stock_est',
                    'format' => 'integer',
                    'headerOptions' => [
                        'width' => 100,
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-right',
                    ],
                ],
                [
                    'attribute' => 'isbn',
                    'value' => function($model) {
                        $html = '{isbn}<div><small class="text-muted">{created_at}</small></div>';

                        $replace = [
                            '{isbn}' => Html::a(Html::encode($model->isbn), ['update', 'id' => $model->id], ['data-pjax' => '0']),
                            '{created_at}' => Yii::$app->formatter->asDatetime($model->created_at),
                        ];
                        return str_replace(array_keys($replace), array_values($replace), $html);
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                ],
                [
                    'attribute' => 'is_hide',
                    'headerOptions' => [
                        'width' => 48,
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                    'value' => function($model) {
                        return Html::a(Html::icon(!$model->is_hide ? 'ok' : 'remove'), ['toggle-status', 'id' => $model->id, 'attribute' => 'is_hide'], [
                                    'class' => !$model->is_hide ? 'text-success' : 'text-danger',
                                    'data-ajax' => '1',
                                    'data-ajax-method' => 'post',
                                    'data-ajax-pjax-reload' => '#pjax-page',
                                    'data-pjax' => '0',
                        ]);
                    },
                    'format' => 'raw',
                ],
                [
                    'header' => 'สำเนา',
                    'class' => ActionColumn::className(),
                    'template' => '{copy}',
                    'buttons' => [
                        'copy' => function($url, $model) {
                            return Html::a(Html::icon('copy'), $url, ['data-pjax' => 0,'data-confirm' => 'ต้องการสำเนารายการนี้?']);
                        },
                    ],
                    'headerOptions' => [
                        'width' => 48,
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update}',
                    'headerOptions' => [
                        'width' => 48,
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center',
                    ],
                ],
                [
                    'class' => DeleteButtonColumn::className(),
                    'headerOptions' => [
                        'width' => 48,
                        'class' => 'text-center',
                    ],
                ],
            ],
        ]);
        ?>
    </div>
</div>
<?php Pjax::end(); ?>
<?php Panel::end(); ?>
<?php JSRegister::begin(); ?>
<script>
    $(document).on("change", ".select-on-check-all, .kv-row-checkbox", function () {
        toggleOperationBar();
    });

    $(document).on("#btn-bulk-delete", function () {
        $.post($(this).attr('href'), {ids: $("#product-grid").yiiGridView("getSelectedRows")}, function () {

        });
        return false;
    });

    $(document).on("click", ".btn-operator", function () {
        var obj = $(this);
        var items = $("#product-grid").yiiGridView("getSelectedRows");
        $.post(obj.data('url'), {
            items: items,
            flag: obj.data('flag'),
            active: obj.hasClass('active')
        }, function (data) {
            $("#operation-frm .btn").prop('disabled', true);
            $.pjax.reload({
                container: '#pjax-page',
                timeout: 5000
            });
        });
    });

    toggleOperationBar();

    function toggleOperationBar() {
        var items = $("#product-grid").yiiGridView("getSelectedRows");
        if (items.length) {
            $("#operation-frm .btn, #btn-bulk-delete").prop('disabled', false);
            $("#btn-bulk-delete").removeClass('disabled');
        } else {
            $("#operation-frm .btn, #btn-bulk-delete").prop('disabled', true);
            $("#btn-bulk-delete").addClass('disabled');
        }
    }
</script>
<?php JSRegister::end(); ?>