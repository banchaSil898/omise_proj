<?php

namespace app\components\grid;

use app\components\Html;
use yii\grid\SerialColumn as BaseSerialColumn;

class SerialColumn extends BaseSerialColumn {

    public function init() {
        parent::init();
        Html::addCssClass($this->headerOptions, 'text-center');
        Html::addCssClass($this->contentOptions, 'text-center');
        $this->headerOptions['width'] = 50;
    }

}
