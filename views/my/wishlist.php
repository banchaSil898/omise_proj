<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\widgets\Pjax;
?>
<?=
Breadcrumbs::widget([
    'enableSearch' => false,
    'items' => [
        [
            'label' => 'รายชื่อหนังสือถูกใจ',
            'url' => ['/wishlist/index'],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => 'รายชื่อหนังสือถูกใจ',
    ]);
    ?>
    <?php
    Pjax::begin([
        'id' => 'pjax-page',
    ]);
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyCell' => '',
        'tableOptions' => [
            'class' => 'table table-white table-sm',
        ],
        'columns' => [
            [
                'class' => SerialColumn::className(),
            ],
            [
                'attribute' => 'cover_url',
                'value' => function($model) {
                    return Html::a(Html::img($model->thumbUrl ? $model->thumbUrl : Html::placeholder(48, 65), ['width' => 48]), $model->seoUrl, ['data-pjax' => '0']);
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
                        '{product}' => Html::a(Html::encode($model->name), $model->seoUrl, ['data-pjax' => '0']),
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
                'header' => 'ลบ',
                'class' => ActionColumn::className(),
                'template' => '{wishlist-delete}',
                'buttons' => [
                    'wishlist-delete' => function($url) {
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
    <?php Pjax::end(); ?>
    <?php Page::end(); ?>
</div>
