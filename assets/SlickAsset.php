<?php

namespace app\assets;

use yii\web\AssetBundle;

class SlickAsset extends AssetBundle {

    public $basePath = '@webroot/js/vendor/slick-1.8.1/slick';
    public $baseUrl = '@web/js/vendor/slick-1.8.1/slick';
    public $js = [
        'slick.min.js',
    ];
    public $css = [
        'slick.css',
        'slick-theme.css',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];

}
