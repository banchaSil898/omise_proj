<?php

namespace app\widgets;

use codesk\components\Html;
use yii\bootstrap\Widget;

class StarRating extends Widget {

    public $max = 5;
    public $score = 0;

    public function run() {
        echo Html::beginTag('div', ['class' => 'star-rating']);
        for ($i = 0; $i < $this->max; $i++) {
            echo Html::tag('i', '', ['class' => 'fa fa-star' . ($i < $this->score ? ' color' : '' )]);
        }
        echo Html::endTag('div');
    }

}
