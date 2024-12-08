<?php

namespace app\widgets;

use yii\base\Widget;

class ProductCarousel extends Widget {

    const TYPE_BOOK = 'books';
    const TYPE_AUTHOR = 'authors';

    public $title;
    public $type = 'books';
    public $subtitle;
    public $itemView = '/widgets/carousel-book';
    public $containerOptions = [
        'class' => 'wow fadeInUp',
    ];
    public $itemOptions = [];
    public $dataProvider;

    public function run() {
        echo $this->render('/widgets/carousel', [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'dataProvider' => $this->dataProvider,
            'itemOptions' => $this->itemOptions,
            'widget' => $this,
        ]);
    }

}
