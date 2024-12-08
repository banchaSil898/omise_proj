<?php

use app\models\Contact;
use codesk\components\Html;
use kartik\form\ActiveForm;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;
?>
<?php

Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<?php

Panel::begin([
    'header' => 'จัดการข้อมูลสอบถาม',
    'icon' => 'globe',
]);
?>
<?php

$form = ActiveForm::begin([
            'type' => 'inline',
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => '',
                'class' => 'text-right',
            ],
        ]);
?>
<?=

$form->field($model, 'search[text]', [
    'addon' => [
        'prepend' => [
            'content' => Html::icon('search'),
        ],
    ],
])->textInput([
    'placeholder' => 'ค้นหา...',
]);
?>
<?php ActiveForm::end(); ?>
<?=

GridView::widget([
    'responsiveWrap' => false,
    'dataProvider' => $dataProvider,
    'rowOptions' => function($model) {
        return ['class' => $model->record_status === Contact::STATUS_COMPLETED ? 'text-blur' : ''];
    },
    'columns' => [
        [
            'class' => SerialColumn::className(),
        ],
        [
            'attribute' => 'name',
            'value' => function($model) {
                return Html::encode($model->name) . Html::tag('div', Html::tag('small', Html::encode($model->email), ['class' => 'text-muted']));
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'contact_type_id',
            'value' => function($model) {
                return $model->contactType->name;
            },
        ],
        [
            'attribute' => 'description',
            'value' => function($model) {
                return mb_substr($model->description, 0, 60) . '...';
            },
        ],
        [
            'attribute' => 'created_at',
            'format' => 'relativeTime',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
        ],
        [
            'attribute' => 'record_status',
            'value' => function($model) {
                return $model->getHtmlRecordStatus();
            },
            'format' => 'html',
            'headerOptions' => [
                'class' => 'text-center',
            ],
            'contentOptions' => [
                'class' => 'text-center',
            ],
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
            'header' => 'ลบ',
            'class' => ActionColumn::className(),
            'template' => '{delete}',
            'buttons' => [
                'delete' => function($url) {
                    return Html::a(Html::icon('trash'), $url, [
                                'data-pjax' => '0',
                                'data-ajax' => '1',
                                'data-ajax-method' => 'post',
                                'data-ajax-pjax-reload' => '#pjax-page',
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

<?php

$this->registerJs(<<<JS
        $(document).ready(function(){
            $("#author-search-text").select();
        });
JS
)?>