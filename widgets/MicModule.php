<?php

namespace app\widgets;

use codesk\components\Html;
use yii\base\Widget;

class MicModule extends Widget {

    public $parentId;
    public $title;
    public $subtitle;
    public $buttons;

    public function init() {
        parent::init();
        echo Html::beginTag('div', [
            'class' => 'module block-featured-author module-block',
        ]);
        echo Html::beginTag('div', [
            'class' => 'module-heading',
        ]);
        echo Html::tag('h2', $this->title . (isset($this->subtitle) ? ' ' . Html::tag('small', $this->subtitle) : ''), [
            'class' => 'module-title',
        ]);
        if (isset($this->buttons) && is_array($this->buttons)) {
            echo Html::beginTag('div', ['class' => 'customNavigation']);
            foreach ($this->buttons as $button) {
                echo Html::a($button['label'], $button['url'], $button['options']) . ' ';
            }
            echo Html::endTag('div');
        }
        echo Html::endTag('div');
        echo Html::beginTag('div', [
            'id' => $this->parentId,
            'class' => 'module-body',
        ]);
    }

    public function run() {
        parent::run();
        echo Html::endTag('div');
        echo Html::endTag('div');
    }

}
