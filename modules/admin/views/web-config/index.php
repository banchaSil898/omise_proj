<?php

use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use yiister\gentelella\widgets\Panel;

?>
<?php

Panel::begin([
    'header' => 'ตั้งค่าเว็บไซต์',
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