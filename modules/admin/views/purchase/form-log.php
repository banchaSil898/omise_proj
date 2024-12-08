<?php

use app\forms\Survey2019;
use yii\widgets\DetailView;
?>
<?php
$this->beginContent('@module/views/purchase/layouts/form.php', [
    'model' => $model,
]);
?>
<h4>แบบสอบถาม ปี 2019</h4>
<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => 'ช่วงอายุ',
            'attribute' => 'survey_2019_age',
            'value' => function($m) {
                return $m->survey_2019_age ? Survey2019::getAgeOptions($m->survey_2019_age) : '';
            },
            'format' => 'text',
        ],
        [
            'label' => 'การศึกษา',
            'attribute' => 'survey_2019_graduate',
            'value' => function($m) {
                return $m->survey_2019_graduate ? Survey2019::getGraduateOptions($m->survey_2019_graduate) : '';
            },
            'format' => 'text',
        ],
        [
            'label' => 'ข้อเสนอแนะ',
            'attribute' => 'survey_2019_comment',
            'format' => 'text',
        ],
    ],
]);
?>
<?= nl2br($model->cart_log); ?>
<?php $this->endContent(); ?>
