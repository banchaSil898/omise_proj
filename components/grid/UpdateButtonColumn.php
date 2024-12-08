<?php

namespace app\components\grid;

use yii\bootstrap\Html;
use yii\grid\ActionColumn;

class UpdateButtonColumn extends ActionColumn {

    public $name = 'update';
    public $header = 'แก้ไข';
    public $icon = 'pencil';
    public $headerOptions = [
        'width' => '50',
        'class' => 'text-center',
    ];
    public $contentOptions = [
        'class' => 'text-center',
    ];
    public $buttonOptions;

    public function init() {
        parent::init();
        $this->template = '{' . $this->name . '}';
        $this->buttons = [
            $this->name => function($url, $model, $key) {
                return Html::a(Html::icon($this->icon), $url, isset($this->buttonOptions) ? $this->buttonOptions : ['data-pjax' => 0]);
            },
        ];
    }

}
