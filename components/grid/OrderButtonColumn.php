<?php

namespace app\components\grid;

use yii\bootstrap\Html;
use yii\grid\ActionColumn;

class OrderButtonColumn extends ActionColumn {

    public $header = 'ลำดับ';
    public $headerOptions = [
        'width' => 60,
        'class' => 'text-center',
    ];
    public $contentOptions = [
        'class' => 'text-center',
    ];

    public function init() {
        parent::init();
        $this->template = '{order-down} {order-up}';
        $this->buttons = [
            'order-down' => function($url, $model, $key) {
                return Html::a(Html::icon('arrow-down'), $url, [
                            'title' => 'เลื่อนลง',
                            'data-pjax' => 0,
                            'data-ajax' => 1,
                            'data-ajax-method' => 'post',
                            'data-ajax-pjax-reload' => '#pjax-page',
                ]);
            },
            'order-up' => function($url, $model, $key) {
                return Html::a(Html::icon('arrow-up'), $url, [
                            'title' => 'เลื่อนขึ้น',
                            'data-pjax' => 0,
                            'data-ajax' => 1,
                            'data-ajax-method' => 'post',
                            'data-ajax-pjax-reload' => '#pjax-page',
                ]);
            },
        ];
    }

}
