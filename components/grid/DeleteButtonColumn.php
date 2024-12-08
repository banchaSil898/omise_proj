<?php

namespace app\components\grid;

use yii\bootstrap\Html;
use yii\grid\ActionColumn;

class DeleteButtonColumn extends ActionColumn {

    public $name = 'delete';
    public $header = 'ลบ';
    public $icon = 'trash';
    public $headerOptions = [
        'width' => '50',
        'class' => 'text-center',
    ];
    public $contentOptions = [
        'class' => 'text-center',
    ];

    public function init() {
        parent::init();
        $this->template = '{' . $this->name . '}';
        $this->buttons = [
            $this->name => function($url, $model, $key) {
                return Html::a(Html::icon($this->icon), $url, [
                            'title' => 'ลบข้อมูล',
                            'data-ajax-confirm' => 'ต้องการลบรายการนี้?',
                            'data-pjax' => 0,
                            'data-ajax' => 1,
                            'data-ajax-method' => 'post',
                            'data-ajax-pjax-reload' => '#pjax-page',
                ]);
            }
        ];
    }

}
