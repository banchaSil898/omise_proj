<?php

use app\components\Html;
use app\models\Folder;
use kartik\form\ActiveForm;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use richardfan\widget\JSRegister;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
?>
<?php
$this->beginContent('@module/views/promotion/layouts/form.php', [
    'model' => $model,
    'showSubmit' => false,
]);
?>
<div class="row">
    <div class="col-sm-6">
        <?php
        Pjax::begin([
            'id' => 'promotion-pjax',
        ]);
        ?>
        <div class="well well-sm">
            <h5 class="text-bold">สินค้าในโปรโมชั่น</h5>
            <?php
            $form = ActiveForm::begin([
                        'id' => 'product-frm-remove-search',
                        'type' => 'horizontal',
                        'method' => 'get',
                        'action' => ['update-product', 'id' => $model->id],
                        'options' => [
                            'data-pjax' => 1,
                        ],
            ]);
            ?>
            <?=
            $form->field($item, 'search[text]')->label('ค้นหา')->textInput();
            ?>
            <?=
            $form->field($item, 'search[folder_id]')->label('หมวดหมู่')->dropDownList(ArrayHelper::map(Folder::find()->all(), 'id', 'name'), [
                'prompt' => '(ทุกหมวดหมู่)',
            ]);
            ?>
            <?=
            $form->field($product, 'info_publisher')->textInput();
            ?>
            <div class="form-group">
                <label class="col-sm-2 control-label">ตัวเลือก</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($item, 'is_own')->checkbox(['uncheck' => null]); ?>
                            <?= $form->field($item, 'is_new')->checkbox(['uncheck' => null]); ?>
                            <?= $form->field($item, 'is_bestseller')->checkbox(['uncheck' => null]); ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($item, 'is_recommended')->checkbox(['uncheck' => null]); ?>
                            <?= $form->field($item, 'is_promotion')->checkbox(['uncheck' => null]); ?>
                            <?= $form->field($item, 'is_hide')->checkbox(['uncheck' => null]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <?=
                Html::submitButton(Html::icon('search') . ' ค้นหา', [
                    'class' => 'btn btn-default pull-right',
                ]);
                ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <?php
        $form = ActiveForm::begin([
                    'id' => 'product-frm-remove',
                    'method' => 'post',
                    'action' => ['product-remove', 'id' => $model->id],
                    'options' => [
                        'data-pjax' => 0,
                    ],
        ]);
        ?>
        <?=
        Html::submitButton('ลบออก (<span id="product-remove-count">0</span>) ' . Html::icon('chevron-right'), [
            'id' => 'btn-remove',
            'class' => 'btn btn-default pull-right',
            'disabled' => true,
        ]);
        ?>
        <?=
        Html::button('เลือกทั้งหมด', [
            'id' => 'btn-product-remove-all',
            'class' => 'btn btn-default'
        ]);
        ?>
        <?=
        Html::hiddenInput('all', 0, [
        ]);
        ?>
        <?=
        Html::hiddenInput('condition', '', [
        ]);
        ?>
        <?=
        Html::hiddenInput('text', $product->search['text'], [
        ]);
        ?>
        <?=
        Html::hiddenInput('folder_id', $product->search['folder_id'], [
        ]);
        ?>
        <?=
        Html::hiddenInput('all_count', Yii::$app->formatter->asInteger($itemProvider->totalCount), [
            'id' => 'promotion-all-count',
        ]);
        ?>
        <?php ActiveForm::end(); ?>
        <?=
        GridView::widget([
            'id' => 'grid-promotion',
            'layout' => '{items} {summary} {pager}',
            'dataProvider' => $itemProvider,
            'columns' => [
                [
                    'class' => CheckboxColumn::className(),
                ],
                [
                    'attribute' => 'name',
                    'value' => function($model) {
                        return Html::tag('div', Html::encode($model->name), ['class' => 'text-bold']) . Html::tag('div', Html::tag('small', Html::encode($model->sku), ['class' => 'text-muted']));
                    },
                    'format' => 'raw',
                ],
            ],
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
    <div class="col-sm-6">
        <?php
        Pjax::begin([
            'id' => 'product-pjax',
        ]);
        ?>
        <div class="well well-sm">
            <h5 class="text-bold">เลือกสินค้าเข้าโปรโมชั่น</h5>
            <?php
            $form = ActiveForm::begin([
                        'id' => 'product-frm-import-search',
                        'type' => 'horizontal',
                        'method' => 'get',
                        'action' => ['update-product', 'id' => $model->id],
                        'options' => [
                            'data-pjax' => 1,
                        ],
            ]);
            ?>
            <?=
            $form->field($product, 'search[text]')->label('ค้นหา')->textInput();
            ?>
            <?=
            $form->field($product, 'search[folder_id]')->label('หมวดหมู่')->dropDownList(Folder::getListItems(), [
                'prompt' => '(ทุกหมวดหมู่)',
            ]);
            ?>
            <?=
            $form->field($product, 'info_publisher')->textInput();
            ?>
            <div class="form-group">
                <label class="col-sm-2 control-label">ตัวเลือก</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($product, 'is_own')->checkbox(['uncheck' => null]); ?>
                            <?= $form->field($product, 'is_new')->checkbox(['uncheck' => null]); ?>
                            <?= $form->field($product, 'is_bestseller')->checkbox(['uncheck' => null]); ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($product, 'is_recommended')->checkbox(['uncheck' => null]); ?>
                            <?= $form->field($product, 'is_promotion')->checkbox(['uncheck' => null]); ?>
                            <?= $form->field($product, 'is_hide')->checkbox(['uncheck' => null]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <?=
                Html::submitButton(Html::icon('search') . ' ค้นหา', [
                    'class' => 'btn btn-default pull-right',
                ]);
                ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <?php
        $form = ActiveForm::begin([
                    'id' => 'product-frm-import',
                    'method' => 'post',
                    'action' => ['product-import', 'id' => $model->id],
                    'options' => [
                        'data-pjax' => 0,
                    ],
        ]);
        ?>
        <?=
        Html::submitButton(Html::icon('chevron-left') . ' นำเข้าโปรโมชั่น (<span id="product-select-count">0</span>)', [
            'id' => 'btn-import',
            'class' => 'btn btn-default',
            'disabled' => true,
        ]);
        ?>
        <?=
        Html::button('เลือกทั้งหมด', [
            'id' => 'btn-product-select-all',
            'class' => 'btn btn-default pull-right'
        ]);
        ?>
        <?=
        Html::hiddenInput('all', 0, [
        ]);
        ?>
        <?=
        Html::hiddenInput('condition', '', [
        ]);
        ?>
        <?=
        Html::hiddenInput('text', $product->search['text'], [
        ]);
        ?>
        <?=
        Html::hiddenInput('folder_id', $product->search['folder_id'], [
        ]);
        ?>
        <?=
        Html::hiddenInput('all_count', Yii::$app->formatter->asInteger($productProvider->totalCount), [
            'id' => 'product-all-count',
        ]);
        ?>
        <?php ActiveForm::end(); ?>
        <?=
        GridView::widget([
            'id' => 'grid-product',
            'layout' => '{items} {summary} {pager}',
            'dataProvider' => $productProvider,
            'columns' => [
                [
                    'class' => CheckboxColumn::className(),
                ],
                [
                    'attribute' => 'name',
                    'value' => function($model) {
                        return Html::tag('div', Html::encode($model->name), ['class' => 'text-bold']) . Html::tag('div', Html::tag('small', Html::encode($model->sku), ['class' => 'text-muted']));
                    },
                    'format' => 'raw',
                ],
            ],
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>

<?php $this->endContent(); ?>
<?php JSRegister::begin(); ?>
<script>
    $(document).ready(function () {

        $(document).on('change', "#grid-product .select-on-check-all, #grid-product .kv-row-checkbox", function () {
            productGridUpdate();
        });

        $(document).on('change', "#grid-promotion .select-on-check-all, #grid-promotion .kv-row-checkbox", function () {
            promotionGridUpdate();
        });

        $(document).on('click', '#btn-product-select-all', function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $('#product-select-count').text('0');
                setProductButton(false);
                $('#grid-product .select-on-check-all, #grid-product .kv-row-checkbox').prop('disabled', false);
                $('#product-frm-import input[name="all"]').val(0);
                $('#product-frm-import input[name="condition"]').val('');
            } else {
                $(this).addClass('active');
                $('#product-select-count').text($('#product-all-count').val());
                setProductButton(true);
                $('#grid-product .select-on-check-all, #grid-product .kv-row-checkbox').prop('disabled', true);
                $('#product-frm-import input[name="all"]').val(1);
                $('#product-frm-import input[name="condition"]').val($('#product-frm-import-search').serialize());
            }
        });

        $(document).on('click', '#btn-product-remove-all', function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $('#product-remove-count').text('0');
                setPromotionButton(false);
                $('#grid-promotion .select-on-check-all, #grid-promotion .kv-row-checkbox').prop('disabled', false);
                $('#product-frm-remove input[name="all"]').val(0);
                $('#product-frm-remove input[name="condition"]').val('');
            } else {
                $(this).addClass('active');
                $('#product-remove-count').text($('#promotion-all-count').val());
                setPromotionButton(true);
                $('#grid-promotion .select-on-check-all, #grid-promotion .kv-row-checkbox').prop('disabled', true);
                $('#product-frm-remove input[name="all"]').val(1);
                $('#product-frm-remove input[name="condition"]').val($('#product-frm-remove-search').serialize());
            }
        });

        $(document).on('beforeSubmit', '#product-frm-import', function (e) {
            e.preventDefault();
            var query = $(this).serializeArray();
            query.push({
                name: "items",
                value: $('#grid-product').yiiGridView('getSelectedRows')
            });
            $.post($(this).attr('action'), $.param(query), function (data) {
                if (data.result === 'success') {
                    $.pjax.reload({container: '#promotion-pjax'}).done(function () {
                        $.pjax.reload({container: '#product-pjax'}).done(function () {
                            $.alert({
                                title: 'นำสินค้าเข้าร่วมรายการโปรโมชั่น',
                                content: 'นำเข้าเรียบร้อย จำนวน ' + data.itemCount + ' รายการ'
                            });
                        });
                    });
                }
            });
            return false;
        });

        $(document).on('beforeSubmit', '#product-frm-remove', function (e) {
            e.preventDefault();
            var query = $(this).serializeArray();
            query.push({
                name: "items",
                value: $('#grid-promotion').yiiGridView('getSelectedRows')
            });
            $.post($(this).attr('action'), $.param(query), function (data) {
                if (data.result === 'success') {
                    $.pjax.reload({container: '#promotion-pjax'}).done(function () {
                        $.pjax.reload({container: '#product-pjax'}).done(function () {
                            $.alert({
                                title: 'ถอดสินค้าออกจากโปรโมชั่น',
                                content: 'ถอดสินค้าออก จำนวน ' + data.itemCount + ' รายการ'
                            });
                        });
                    });
                }
            })
            return false;
        });
    });

    function setProductButton(enable) {
        if (enable) {
            $('#btn-import')
                    .removeClass('btn-default')
                    .addClass('btn-primary')
                    .prop('disabled', false);
        } else {
            $('#btn-import')
                    .removeClass('btn-primary')
                    .addClass('btn-default')
                    .prop('disabled', true);

        }
    }

    function setPromotionButton(enable) {
        if (enable) {
            $('#btn-remove')
                    .removeClass('btn-default')
                    .addClass('btn-primary')
                    .prop('disabled', false);
        } else {
            $('#btn-remove')
                    .removeClass('btn-primary')
                    .addClass('btn-default')
                    .prop('disabled', true);

        }
    }

    function productGridUpdate() {
        var items = $('#grid-product').yiiGridView('getSelectedRows');
        $('#product-select-count').text(items.length);
        if (items.length) {
            setProductButton(true);
        } else {
            setProductButton(false);
        }
    }

    function promotionGridUpdate() {
        var items = $('#grid-promotion').yiiGridView('getSelectedRows');
        $('#product-remove-count').text(items.length);
        if (items.length) {
            setPromotionButton(true);
        } else {
            setPromotionButton(false);
        }
    }
</script>
<?php JSRegister::end(); ?>