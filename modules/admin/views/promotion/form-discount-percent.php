<?php

use app\models\Category;
use app\modules\admin\assets\JqueryTreeAsset;
use codesk\components\Html;
use kartik\form\ActiveForm;
?>
<?php

$this->beginContent('@module/views/promotion/layouts/form.php', [
    'model' => $model,
]);
?>
<?php

$form = ActiveForm::begin([
            'type' => 'horizontal',
        ])
?>
<?=

$form->field($model, 'promotion_value', [
    'addon' => [
        'append' => [
            'content' => '%',
        ],
    ],
])->label('ลดราคา')->textInput();
?>
<?php ActiveForm::end(); ?>
<?php $this->endContent(); ?>