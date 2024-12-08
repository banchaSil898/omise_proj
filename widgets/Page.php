<?php

namespace app\widgets;

use codesk\components\Html;
use yii\base\Widget;

class Page extends Widget {

    public $title;
    public $subtitle;

    public function init() {
        parent::init();
        echo Html::beginTag('div', ['class' => 'page-header category-page-header']);
        echo Html::tag('h2', $this->title, ['class' => 'page-title']);
        echo Html::tag('p', $this->subtitle, ['class' => 'page-subtitle']);
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'page-body']);
    }

    public function run() {
        echo Html::endTag('div');
    }

}
