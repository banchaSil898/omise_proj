<?php

use app\modules\admin\assets\HighchartsAsset;
use app\modules\admin\components\Html as AdminHtml;
use kartik\form\ActiveForm;
use miloschuman\highcharts\Highcharts;
use yii\bootstrap\Html;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;

HighchartsAsset::register($this);
?>
<?php
Panel::begin([
    'header' => 'สรุปยอดสั่งซื้อวันนี้ (' . Yii::$app->formatter->asDate($model->from_date) . ')',
    'icon' => 'cubes',
]);
?>
<?php
$form = ActiveForm::begin([
            'type' => 'inline',
            'method' => 'get',
            'action' => ['daily'],
        ]);
?>
<?= Html::submitButton(AdminHtml::awesome('file-excel-o') . ' ส่งออกไฟล์ XLS', ['class' => 'btn btn-success', 'name' => 'mode', 'value' => 'xls']); ?>
<?php ActiveForm::end(); ?>
<div class="row">
    <div class="col-sm-8">
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
    <div class="col-sm-4">
        <?=
        Highcharts::widget([
            'options' => $pieChartOptions,
        ]);
        ?>
    </div>
</div>
<?php Panel::end(); ?>