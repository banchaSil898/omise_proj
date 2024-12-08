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
            <h5 class="text-bold">ของที่ระลึก</h5>
            <?php
            $form = ActiveForm::begin([
                        'type' => 'horizontal',
                        'method' => 'get',
                        'action' => ['update-gift', 'id' => $model->id],
                        'options' => [
                            'data-pjax' => 1,
                        ],
            ]);
            ?>
            <?=
            $form->field($item, 'search[text]')->label('ค้นหา')->textInput();
            ?>
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
                    'action' => ['gift-remove', 'id' => $model->id],
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
        Html::hiddenInput('all', 0, [
        ]);
        ?>
        <?=
        Html::hiddenInput('text', $product->search['text'], [
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
            <h5 class="text-bold">เลือกของที่ระลึกในโปรโมชั่น</h5>
            <?php
            $form = ActiveForm::begin([
                        'type' => 'horizontal',
                        'method' => 'get',
                        'action' => ['update-gift', 'id' => $model->id],
                        'options' => [
                            'data-pjax' => 1,
                        ],
            ]);
            ?>
            <?=
            $form->field($product, 'search[text]')->label('ค้นหา')->textInput();
            ?>
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
                    'action' => ['gift-import', 'id' => $model->id],
                    'options' => [
                        'data-pjax' => 0,
                    ],
        ]);
        ?>
        <?=
        Html::submitButton(Html::icon('chevron-left') . ' นำเข้าของที่ระลึก (<span id="product-select-count">0</span>)', [
            'id' => 'btn-import',
            'class' => 'btn btn-default',
            'disabled' => true,
        ]);
        ?>
        <?=
        Html::hiddenInput('all', 0, [
        ]);
        ?>
        <?=
        Html::hiddenInput('text', $product->search['text'], [
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
            } else {
                $(this).addClass('active');
                $('#product-select-count').text($('#product-all-count').val());
                setProductButton(true);
                $('#grid-product .select-on-check-all, #grid-product .kv-row-checkbox').prop('disabled', true);
                $('#product-frm-import input[name="all"]').val(1);
            }
        });

        $(document).on('click', '#btn-product-remove-all', function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $('#product-remove-count').text('0');
                setPromotionButton(false);
                $('#grid-promotion .select-on-check-all, #grid-promotion .kv-row-checkbox').prop('disabled', false);
                $('#product-frm-remove input[name="all"]').val(0);
            } else {
                $(this).addClass('active');
                $('#product-remove-count').text($('#promotion-all-count').val());
                setPromotionButton(true);
                $('#grid-promotion .select-on-check-all, #grid-promotion .kv-row-checkbox').prop('disabled', true);
                $('#product-frm-remove input[name="all"]').val(1);
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
                                title: 'นำของแถมเข้าร่วมรายการโปรโมชั่น',
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
                                title: 'ถอดของแถมออกจากโปรโมชั่น',
                                content: 'ถอดของแถมออก จำนวน ' + data.itemCount + ' รายการ'
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