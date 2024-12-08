<?php

use app\modules\admin\assets\HighchartsAsset;
use app\modules\admin\components\Html as AdminHtml;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use miloschuman\highcharts\Highcharts;
use yii\bootstrap\Html;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;

HighchartsAsset::register($this);
?>
<?php
Panel::begin([
    'header' => 'สรุปยอดสั่งซื้อรายสัปดาห์',
    'icon' => 'cubes',
]);
?>
<?php
$form = ActiveForm::begin([
            'type' => 'inline',
            'method' => 'get',
            'action' => ['weekly'],
        ]);
?>
<span class="btn" style="margin: 0;">ตั้งแต่</span> 
<?=
$form->field($model, 'from_date')->widget(DatePicker::className(), [
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
    ]
]);
?> 
<span class="btn" style="margin: 0;">ถึง</span> 
<?=
$form->field($model, 'to_date')->widget(DatePicker::className(), [
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
    ]
]);
?> 
<?= Html::submitButton(AdminHtml::awesome('search') . ' แสดงรายงาน', ['class' => 'btn btn-primary', 'name' => 'mode', 'value' => 'default']); ?>
<?= Html::submitButton(AdminHtml::awesome('file-excel-o') . ' ส่งออกไฟล์ XLS', ['class' => 'btn btn-success', 'name' => 'mode', 'value' => 'xls']); ?>
<?php ActiveForm::end(); ?>
<div class="row">
    <div class="col-sm-8">
        <?=
        Highcharts::widget([
            'options' => $areaChartOptions,
        ]);
        ?>
    </div>
    <div class="col-sm-4">
        <?=
        Highcharts::widget([
            'options' => $pieChartOptions,
        ]);
        ?>
    </div>
</div>
<div class="row">
    <?php Pjax::begin(); ?>
    <?=
    $this->render('_grid', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'summary' => $summary,
    ])
    ?>
    <?php Pjax::end(); ?>
</div>
<?php Panel::end(); ?>